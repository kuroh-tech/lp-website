<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/form_options.php';
require_once __DIR__ . '/../app/Validator.php';
require_once __DIR__ . '/../app/InquiryRepository.php';
require_once __DIR__ . '/../app/RateLimiter.php';
require_once __DIR__ . '/../app/MailService.php';

$autoload = __DIR__ . '/../vendor/autoload.php';
if (is_readable($autoload)) {
    require_once $autoload;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method Not Allowed');
}

$options = include __DIR__ . '/../app/form_options.php';
if (!is_array($options)) {
    $options = [];
}

$payload = [
    'company' => normalize_text($_POST['company'] ?? ''),
    'name' => normalize_text($_POST['name'] ?? ''),
    'email' => normalize_text($_POST['email'] ?? ''),
    'inquiry_type' => normalize_text($_POST['inquiry_type'] ?? ''),
    'message' => normalize_text($_POST['message'] ?? ''),
    'privacy' => normalize_text($_POST['privacy'] ?? ''),
    'company_type' => normalize_text($_POST['company_type'] ?? ''),
    'response_style' => normalize_text($_POST['response_style'] ?? ''),
    'desired_timing' => normalize_text($_POST['desired_timing'] ?? ''),
    'budget_range' => normalize_text($_POST['budget_range'] ?? ''),
    'nda' => normalize_text($_POST['nda'] ?? ''),
    'contact_method' => normalize_text($_POST['contact_method'] ?? ''),
];

$honeypot = normalize_text($_POST['website'] ?? '');

$ipAddress = client_ip();
$userAgent = normalize_text($_SERVER['HTTP_USER_AGENT'] ?? '');
$referrer = normalize_text($_SERVER['HTTP_REFERER'] ?? '');

function redirect_form_error(array $payload): never
{
    log_error('form_validation_failed', [
        'email' => masked_email($payload['email'] ?? null),
        'ip' => $payload['ip_address'] ?? null,
        'reason' => 'validation_error',
    ]);

    redirect_to('../index.html#contact', 303);
}

function redirect_system_error(array $payload): never
{
    log_error('contact_system_error', [
        'email' => masked_email($payload['email'] ?? null),
        'ip' => $payload['ip_address'] ?? null,
    ]);

    redirect_to('../index.html#contact', 303);
}

if ($honeypot !== '') {
    try {
        $pdo = new PDO(
            (string) config('db.dsn'),
            (string) config('db.username'),
            (string) config('db.password'),
            (array) config('db.options', []),
        );
        $repo = new InquiryRepository($pdo);
        $repo->logAttempt($ipAddress, null, 'honeypot');
    } catch (Throwable $e) {
        log_error('attempt_log_failed', [
            'event' => 'honeypot',
            'ip' => $ipAddress,
        ]);
    }

    redirect_to('../thanks.html', 303);
}

try {
    $pdo = new PDO(
        (string) config('db.dsn'),
        (string) config('db.username'),
        (string) config('db.password'),
        (array) config('db.options', []),
    );
} catch (Throwable $e) {
    redirect_system_error([
        'email' => $payload['email'],
        'ip_address' => $ipAddress,
    ]);
}

$repo = new InquiryRepository($pdo);

$rateLimiter = new RateLimiter(
    $repo,
    (int) config('app.rate_limit_window_seconds', 300),
    (int) config('app.rate_limit_max_attempts', 3),
);

if ($rateLimiter->isLimited($ipAddress, $payload['email'])) {
    $repo->logAttempt($ipAddress, $payload['email'], 'rate_limited', 'rate limit');
    redirect_system_error([
        'email' => $payload['email'],
        'ip_address' => $ipAddress,
    ]);
}

$validated = Validator::validate($payload, $options);
if (!$validated['valid']) {
    $repo->logAttempt($ipAddress, $payload['email'], 'validation_error');
    redirect_form_error([
        'email' => $payload['email'],
        'ip_address' => $ipAddress,
    ]);
}

$validatedData = $validated['data'];
$meta = [
    'ip_address' => $ipAddress,
    'user_agent' => $userAgent,
    'referrer' => $referrer,
];

try {
    $result = $repo->insertInquiry($validatedData, $meta);
    $inquiryId = (int) $result['id'];
    $publicId = (string) $result['public_id'];

    $mailData = [
        'public_id' => $publicId,
        'created_at' => date('Y-m-d H:i:s'),
        'company' => $validatedData['company'],
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'inquiry_type' => label_from_option('inquiry_type', $validatedData['inquiry_type'], $options),
        'message' => $validatedData['message'],
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'referrer' => $referrer,
    ];

    $mailService = new MailService();
    $adminSent = false;
    $autoReplyStatus = 'skipped';
    $autoSent = false;

    try {
        $mailService->sendAdminNotification($mailData);
        $adminSent = true;
        $autoReplyStatus = 'pending';

        try {
            $mailService->sendAutoReply($mailData);
            $autoSent = true;
            $autoReplyStatus = 'sent';
        } catch (Throwable $e) {
            $repo->logAttempt($ipAddress, $mailData['email'], 'auto_reply_failed');
            log_error('auto_reply_failed', [
                'public_id' => $publicId,
                'email' => masked_email($mailData['email']),
                'ip' => $ipAddress,
            ]);
        }
    } catch (Throwable $e) {
        log_error('admin_mail_failed', [
            'public_id' => $publicId,
            'email' => masked_email($mailData['email']),
            'ip' => $ipAddress,
            'error' => $e->getMessage(),
        ]);
    }

    $repo->updateMailStatuses(
        $inquiryId,
        $adminSent ? 'sent' : 'failed',
        $autoSent ? 'sent' : $autoReplyStatus
    );
    $repo->logAttempt($ipAddress, $mailData['email'], 'success');

    redirect_to('../thanks.html', 303);
} catch (Throwable $e) {
    if (isset($repo)) {
        $repo->logAttempt($ipAddress, $payload['email'], 'db_error');
    }

    log_error('db_error', [
        'event' => 'db_error',
        'email' => masked_email($payload['email']),
        'ip' => $ipAddress,
        'error' => $e->getMessage(),
    ]);

    redirect_system_error([
        'email' => $payload['email'],
        'ip_address' => $ipAddress,
    ]);
}
