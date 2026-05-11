<?php

declare(strict_types=1);

return [
    'env' => 'production',
    'debug' => false,
    'base_url' => 'https://example.com',
    'admin_email' => 'your-admin@example.com',
    'admin_name' => 'DF CONNECT',
    'from_email' => 'no-reply@example.com',
    'from_name' => 'DF CONNECT',
    'reply_to_name_suffix' => ' 様',
    'timezone' => 'Asia/Tokyo',
    'rate_limit_window_seconds' => 300,
    'rate_limit_max_attempts' => 3,
    'log_file' => __DIR__ . '/../storage/logs/contact_error.log',
    'session_secure' => true,
];
