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
    'company_type' => normalize_text($_POST['company_type'] ?? ''),
    'inquiry_type' => normalize_text($_POST['inquiry_type'] ?? ''),
    'response_style' => normalize_text($_POST['response_style'] ?? ''),
    'desired_timing' => normalize_text($_POST['desired_timing'] ?? ''),
    'budget_range' => normalize_text($_POST['budget_range'] ?? ''),
    'nda' => normalize_text($_POST['nda'] ?? ''),
    'contact_method' => normalize_text($_POST['contact_method'] ?? ''),
    'message' => normalize_text($_POST['message'] ?? ''),
    'privacy' => normalize_text($_POST['privacy'] ?? ''),
];

$honeypot = normalize_text($_POST['website'] ?? '');
$csrfToken = normalize_text($_POST['csrf_token'] ?? '');

$ipAddress = client_ip();
$userAgent = normalize_text($_SERVER['HTTP_USER_AGENT'] ?? '');
$referrer = normalize_text($_SERVER['HTTP_REFERER'] ?? '');

function redirect_with_error(array $errors, array $fieldErrors, array $payload, string $errorMessage = ''): never
{
    flash_set('errors', $errors);
    flash_set('field_errors', $fieldErrors);
    if ($errorMessage !== '') {
        flash_set('system_error', $errorMessage);
    }
    flash_set('old', [
        'company' => $payload['company'],
        'name' => $payload['name'],
        'email' => $payload['email'],
        'company_type' => $payload['company_type'],
        'inquiry_type' => $payload['inquiry_type'],
        'response_style' => $payload['response_style'],
        'desired_timing' => $payload['desired_timing'],
        'budget_range' => $payload['budget_range'],
        'nda' => $payload['nda'],
        'contact_method' => $payload['contact_method'],
        'message' => $payload['message'],
        'privacy' => $payload['privacy'],
    ]);

    redirect_to('/#contact-form', 303);
}

function label_from_option(string $field, string $value, array $options): string
{
    return isset($options[$field][$value]) ? (string) $options[$field][$value] : $value;
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

    redirect_to('/thanks.html', 303);
}

try {
    $pdo = new PDO(
        (string) config('db.dsn'),
        (string) config('db.username'),
        (string) config('db.password'),
        (array) config('db.options', []),
    );
} catch (Throwable $e) {
    log_error('db_connect_failed', [
        'event' => 'db_connect_failed',
        'email' => masked_email($payload['email']),
        'ip' => $ipAddress,
    ]);

    redirect_with_error(
        ['送信に失敗しました。時間をおいて再度お試しください。'],
        ['system' => '送信に失敗しました。時間をおいて再度お試しください。'],
        $payload,
        '送信に失敗しました。時間をおいて再度お試しください。'
    );
}

$repo = new InquiryRepository($pdo);

$rateLimiter = new RateLimiter(
    $repo,
    (int) config('app.rate_limit_window_seconds', 300),
    (int) config('app.rate_limit_max_attempts', 3),
);

if ($rateLimiter->isLimited($ipAddress, $payload['email'])) {
    $repo->logAttempt($ipAddress, $payload['email'], 'rate_limited', 'rate limit');
    redirect_with_error(
        ['短時間に複数回送信されています。時間をおいて再度お試しください。'],
        ['system' => '短時間に複数回送信されています。時間をおいて再度お試しください。'],
        $payload
    );
}

if (!Csrf::validate($csrfToken)) {
    $repo->logAttempt($ipAddress, $payload['email'], 'csrf_error');
    redirect_with_error(
        ['送信内容の確認に失敗しました。お手数ですが、ページを再読み込みして再度お試しください。'],
        ['csrf' => '送信内容の確認に失敗しました。お手数ですが、ページを再読み込みして再度お試しください。'],
        $payload
    );
}

$validated = Validator::validate($payload, $options);
if (!$validated['valid']) {
    $repo->logAttempt($ipAddress, $payload['email'], 'validation_error');
    redirect_with_error($validated['errors'], $validated['field_errors'], $validated['data']);
}

$validatedData = $validated['data'];
$validatedData['company_type'] = $validatedData['company_type'] === '' ? '' : $validatedData['company_type'];
$validatedData['response_style'] = $validatedData['response_style'] === '' ? '' : $validatedData['response_style'];
$validatedData['desired_timing'] = $validatedData['desired_timing'] === '' ? '' : $validatedData['desired_timing'];
$validatedData['budget_range'] = $validatedData['budget_range'] === '' ? '' : $validatedData['budget_range'];
$validatedData['nda'] = $validatedData['nda'] === '' ? '' : $validatedData['nda'];
$validatedData['contact_method'] = $validatedData['contact_method'] === '' ? '' : $validatedData['contact_method'];

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
        'company_type' => label_from_option('company_type', $validatedData['company_type'], $options),
        'inquiry_type' => label_from_option('inquiry_type', $validatedData['inquiry_type'], $options),
        'response_style' => label_from_option('response_style', $validatedData['response_style'], $options),
        'desired_timing' => label_from_option('desired_timing', $validatedData['desired_timing'], $options),
        'budget_range' => label_from_option('budget_range', $validatedData['budget_range'], $options),
        'nda' => label_from_option('nda', $validatedData['nda'], $options),
        'contact_method' => label_from_option('contact_method', $validatedData['contact_method'], $options),
        'message' => $validatedData['message'],
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'referrer' => $referrer,
    ];

    $mailService = new MailService();

    try {
        $mailService->sendAdminNotification($mailData);
        $autoReplyStatus = 'pending';

        try {
            $mailService->sendAutoReply($mailData);
            $autoReplyStatus = 'sent';
        } catch (Throwable $e) {
            $autoReplyStatus = 'failed';
            $repo->logAttempt($ipAddress, $mailData['email'], 'auto_reply_failed');
            log_error('auto_reply_failed', [
                'public_id' => $publicId,
                'email' => masked_email($mailData['email']),
                'ip' => $ipAddress,
            ]);
        }

        $repo->updateMailStatuses($inquiryId, 'sent', $autoReplyStatus);
        $repo->logAttempt($ipAddress, $mailData['email'], 'success');
        Csrf::clear();
        redirect_to('/thanks.html', 303);
    } catch (Throwable $e) {
        $repo->updateMailStatuses($inquiryId, 'failed', 'skipped');
        $repo->logAttempt($ipAddress, $mailData['email'], 'admin_mail_failed');
        log_error('admin_mail_failed', [
            'public_id' => $publicId,
            'email' => masked_email($mailData['email']),
            'ip' => $ipAddress,
            'error' => $e->getMessage(),
        ]);

        redirect_with_error(
            ['送信に失敗しました。時間をおいて再度お試しください。'],
            ['system' => '送信に失敗しました。時間をおいて再度お試しください。'],
            $payload
        );
    }
} catch (Throwable $e) {
    $repo->logAttempt($ipAddress, $payload['email'], 'db_error');
    log_error('db_error', [
        'event' => 'db_error',
        'email' => masked_email($payload['email']),
        'ip' => $ipAddress,
        'error' => $e->getMessage(),
    ]);

    redirect_with_error(
        ['送信に失敗しました。時間をおいて再度お試しください。'],
        ['system' => '送信に失敗しました。時間をおいて再度お試しください。'],
        $payload
    );
}
