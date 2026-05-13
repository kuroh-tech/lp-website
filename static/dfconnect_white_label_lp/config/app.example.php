<?php

declare(strict_types=1);

return [
    'env' => 'production',
    'debug' => false,
    'base_url' => 'https://dfconnect.jp',
    'admin_email' => 'your-admin@example.com',
    'admin_name' => 'DFConnect',
    'from_email' => 'no-reply@dfconnect.jp',
    'from_name' => 'DFConnect',
    'reply_to_name_suffix' => ' 様',
    'timezone' => 'Asia/Tokyo',
    'rate_limit_window_seconds' => 300,
    'rate_limit_max_attempts' => 3,
    'csrf_token_ttl_seconds' => 1800,
    'log_file' => __DIR__ . '/../storage/logs/contact_error.log',
    'session_secure' => true,
];
