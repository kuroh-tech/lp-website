<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

$baseDir = dirname(__DIR__);
$configDir = $baseDir . '/config';

function load_config_file(string $path, string $examplePath): array
{
    if (is_readable($path)) {
        $config = include $path;
        return is_array($config) ? $config : [];
    }

    if (is_readable($examplePath)) {
        $config = include $examplePath;
        return is_array($config) ? $config : [];
    }

    return [];
}

$defaultAppConfig = [
    'env' => 'production',
    'debug' => false,
    'base_url' => 'https://dfconnect.jp',
    'admin_email' => 'your-admin@example.com',
    'admin_name' => 'DFConnect',
    'from_email' => 'no-reply@dfconnect.jp',
    'from_name' => 'DFConnect',
    'reply_to_name_suffix' => '様',
    'timezone' => 'Asia/Tokyo',
    'rate_limit_window_seconds' => 300,
    'rate_limit_max_attempts' => 3,
    'csrf_token_ttl_seconds' => 1800,
    'log_file' => $baseDir . '/storage/logs/contact_error.log',
    'session_secure' => true,
];

$defaultDbConfig = [
    'dsn' => 'mysql:host=127.0.0.1;dbname=dfconnect_lp;charset=utf8mb4',
    'username' => '<xserver-db-user>',
    'password' => '<xserver-db-password>',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];

$defaultMailConfig = [
    'host' => 'smtp.xserver.ne.jp',
    'port' => 465,
    'encryption' => 'ssl',
    'username' => 'no-reply@dfconnect.jp',
    'password' => '<xserver-smtp-password>',
    'smtp_auth' => true,
    'charset' => 'UTF-8',
    'admin_subject' => '【DFConnect】お問い合わせがありました',
    'auto_reply_subject' => '【DFConnect】お問い合わせありがとうございます',
];

$appConfig = array_replace_recursive($defaultAppConfig, load_config_file($configDir . '/app.php', $configDir . '/app.example.php'));
$dbConfig = array_replace_recursive($defaultDbConfig, load_config_file($configDir . '/db.php', $configDir . '/db.example.php'));
$mailConfig = array_replace_recursive($defaultMailConfig, load_config_file($configDir . '/mail.php', $configDir . '/mail.example.php'));

$CONFIG = [
    'app' => $appConfig,
    'db' => $dbConfig,
    'mail' => $mailConfig,
];

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        global $CONFIG;
        $parts = explode('.', $key);
        $value = $CONFIG;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }

            $value = $value[$part];
        }

        return $value;
    }
}

$timezone = (string) config('app.timezone', 'Asia/Tokyo');
date_default_timezone_set($timezone);

$sessionSecure = (bool) config('app.session_secure', true);
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === '' || $_SERVER['HTTPS'] === 'off') {
    // Local or non-SSL environments
    $sessionSecure = false;
}

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $sessionSecure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!is_array($_SESSION)) {
    $_SESSION = [];
}

if (!config('app.debug', false)) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
}
ini_set('log_errors', '1');

require_once __DIR__ . '/Csrf.php';
