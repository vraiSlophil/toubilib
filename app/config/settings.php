<?php

use toubilib\core\application\ports\spi\adapterInterface\MonologLoggerInterface;
use toubilib\infra\adapters\MonologLogger;

return [
    'settings' => [
        'displayErrorDetails' => true,
        'logError'            => true,
        'logErrorDetails'     => true,
        'logs_dir' => __DIR__ . '/../var/logs',
    ],

    'db.praticien' => static function (): PDO {
        $driver = $_ENV['prat.driver'];
        $host = $_ENV['prat.host'];
        $db = $_ENV['prat.database'];
        $user = $_ENV['prat.username'];
        $pass = $_ENV['prat.password'];
        $charset = 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$db};charset={$charset}"
            : "pgsql:host={$host};dbname={$db}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },

    'db.rdv' => static function (): PDO {
        $driver = $_ENV['rdv.driver'];
        $host = $_ENV['rdv.host'];
        $db = $_ENV['rdv.database'];
        $user = $_ENV['rdv.username'];
        $pass = $_ENV['rdv.password'];
        $charset = 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$db};charset={$charset}"
            : "pgsql:host={$host};dbname={$db}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },

    'db.patient' => static function (): PDO {
        $driver = $_ENV['pat.driver'];
        $host = $_ENV['pat.host'];
        $db = $_ENV['pat.database'];
        $user = $_ENV['pat.username'];
        $pass = $_ENV['pat.password'];
        $charset = 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$db};charset={$charset}"
            : "pgsql:host={$host};dbname={$db}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },

    'db.authentification' => static function (): PDO {
        $driver = $_ENV['auth.driver'];
        $host = $_ENV['auth.host'];
        $db = $_ENV['auth.database'];
        $user = $_ENV['auth.username'];
        $pass = $_ENV['auth.password'];
        $charset = 'utf8mb4';

        $dsn = $driver === 'mysql'
            ? "mysql:host={$host};dbname={$db};charset={$charset}"
            : "pgsql:host={$host};dbname={$db}";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    },

    MonologLoggerInterface::class => static function ($c) {
        return new MonologLogger($c);
    },
];