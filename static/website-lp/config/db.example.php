<?php

declare(strict_types=1);

return [
    'dsn' => 'mysql:host=<xserver-db-host>;dbname=<xserver-db-name>;charset=utf8mb4',
    'username' => '<xserver-db-user>',
    'password' => '<xserver-db-password>',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
