<?php

declare(strict_types=1);

final class Csrf
{
    public static function token(): string
    {
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_created_at'])) {
            return self::generate();
        }

        $ttl = (int) config('app.csrf_token_ttl_seconds', 1800);
        $issued = (int) $_SESSION['csrf_token_created_at'];
        if ((time() - $issued) > $ttl) {
            return self::generate();
        }

        return (string) $_SESSION['csrf_token'];
    }

    public static function validate(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_created_at'])) {
            return false;
        }

        $ttl = (int) config('app.csrf_token_ttl_seconds', 1800);
        if ((time() - (int) $_SESSION['csrf_token_created_at']) > $ttl) {
            return false;
        }

        return hash_equals((string) $_SESSION['csrf_token'], (string) $token);
    }

    public static function clear(): void
    {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_created_at']);
    }

    private static function generate(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_created_at'] = time();

        return $token;
    }
}
