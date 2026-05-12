<?php

declare(strict_types=1);

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function normalize_text(?string $value): string
{
    if ($value === null) {
        return '';
    }

    $value = str_replace("\0", '', $value);
    $value = trim($value);
    $value = preg_replace("/\r\n?|\n/", "\n", $value);

    return $value;
}

function flash_set(string $key, mixed $value): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $_SESSION['_flash'][$key] = $value;
}

function flash_get(string $key, mixed $default = null): mixed
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return $default;
    }

    if (!isset($_SESSION['_flash'][$key])) {
        return $default;
    }

    $value = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function redirect_to(string $path, int $statusCode = 303): void
{
    header("Location: {$path}", true, $statusCode);
    exit;
}

function client_ip(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $candidate = trim((string) explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        if ($candidate !== '') {
            return $candidate;
        }
    }

    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return (string) $_SERVER['REMOTE_ADDR'];
    }

    return '';
}

function masked_email(?string $email): string
{
    if ($email === null || $email === '') {
        return 'n/a';
    }

    $parts = explode('@', $email, 2);
    if (count($parts) !== 2) {
        return '***';
    }

    [$local, $domain] = $parts;
    if ($local === '') {
        return '***@' . $domain;
    }

    if (strlen($local) === 1) {
        return $local . '***@' . $domain;
    }

    return substr($local, 0, 1) . '***@' . $domain;
}

function log_error(string $event, array $context = []): void
{
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $event;

    if (!empty($context)) {
        $parts = [];
        foreach ($context as $key => $value) {
            if (is_scalar($value)) {
                $parts[] = $key . '=' . (string) $value;
            }
        }

        if ($parts !== []) {
            $line .= ' ' . implode(' ', $parts);
        }
    }

    $line .= "\n";

    if (function_exists('config')) {
        $logFile = (string) config('app.log_file', '');
        if ($logFile !== '' && is_writable(dirname($logFile))) {
            file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
            return;
        }
    }

    error_log($line);
}

function label_from_option(string $field, string $value, array $options): string
{
    return isset($options[$field][$value]) ? (string) $options[$field][$value] : $value;
}
