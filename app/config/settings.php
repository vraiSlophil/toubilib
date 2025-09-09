<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'logError'            => true,
        'logErrorDetails'     => true,
        // Database settings
        'toubiprati.db' => [
            'host' => 'toubiprat',
            'user' => 'toubiprat',
            'pass' => 'toubiprat',
            'dbname' => 'toubiprat',
            'charset' => 'utf8mb4',
        ],
    ],

//    // ================== CONFIGURATION GÉNÉRALE ==================
//    'app' => [
//        'name' => $_ENV['APP_NAME'] ?? 'ToubiLib API',
//        'version' => $_ENV['APP_VERSION'] ?? '1.0.0',
//        'environment' => $_ENV['APP_ENV'] ?? 'development',
//        'timezone' => $_ENV['APP_TIMEZONE'] ?? 'Europe/Paris',
//    ],
//
//    // ================== CONFIGURATION SLIM ==================
//    'slim' => [
//        'displayErrorDetails' => (bool) ($_ENV['DISPLAY_ERROR_DETAILS'] ?? true),
//        'logErrors' => (bool) ($_ENV['LOG_ERRORS'] ?? true),
//        'logErrorDetails' => (bool) ($_ENV['LOG_ERROR_DETAILS'] ?? true),
//        'addContentLengthHeader' => false, // Évite les conflits avec output buffering
//    ],
//
//    // ================== CONFIGURATION BASES DE DONNÉES ==================
//    'databases' => [
//        'praticien' => [
//            'driver' => 'pgsql',
//            'host' => $_ENV['DB_PRATICIEN_HOST'] ?? 'localhost',
//            'port' => (int) ($_ENV['DB_PRATICIEN_PORT'] ?? 5432),
//            'database' => $_ENV['DB_PRATICIEN_NAME'] ?? 'toubiprat',
//            'username' => $_ENV['DB_PRATICIEN_USER'] ?? 'toubi',
//            'password' => $_ENV['DB_PRATICIEN_PASS'] ?? 'password',
//            'charset' => 'utf8',
//            'options' => [
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//                PDO::ATTR_EMULATE_PREPARES => false,
//            ]
//        ],
//
//        'auth' => [
//            'driver' => 'pgsql',
//            'host' => $_ENV['DB_AUTH_HOST'] ?? 'localhost',
//            'port' => (int) ($_ENV['DB_AUTH_PORT'] ?? 5433),
//            'database' => $_ENV['DB_AUTH_NAME'] ?? 'toubiauth',
//            'username' => $_ENV['DB_AUTH_USER'] ?? 'toubi',
//            'password' => $_ENV['DB_AUTH_PASS'] ?? 'password',
//            'charset' => 'utf8',
//            'options' => [
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//                PDO::ATTR_EMULATE_PREPARES => false,
//            ]
//        ],
//
//        'rdv' => [
//            'driver' => 'pgsql',
//            'host' => $_ENV['DB_RDV_HOST'] ?? 'localhost',
//            'port' => (int) ($_ENV['DB_RDV_PORT'] ?? 5434),
//            'database' => $_ENV['DB_RDV_NAME'] ?? 'toubirdv',
//            'username' => $_ENV['DB_RDV_USER'] ?? 'toubi',
//            'password' => $_ENV['DB_RDV_PASS'] ?? 'password',
//            'charset' => 'utf8',
//            'options' => [
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//                PDO::ATTR_EMULATE_PREPARES => false,
//            ]
//        ],
//
//        'patient' => [
//            'driver' => 'pgsql',
//            'host' => $_ENV['DB_PATIENT_HOST'] ?? 'localhost',
//            'port' => (int) ($_ENV['DB_PATIENT_PORT'] ?? 5435),
//            'database' => $_ENV['DB_PATIENT_NAME'] ?? 'toubipatient',
//            'username' => $_ENV['DB_PATIENT_USER'] ?? 'toubi',
//            'password' => $_ENV['DB_PATIENT_PASS'] ?? 'password',
//            'charset' => 'utf8',
//            'options' => [
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//                PDO::ATTR_EMULATE_PREPARES => false,
//            ]
//        ],
//    ],
//
//    // ================== CONFIGURATION LOGGING ==================
//    'logger' => [
//        'name' => $_ENV['LOG_NAME'] ?? 'toubilib',
//        'path' => $_ENV['LOG_PATH'] ?? __DIR__ . '/../var/logs/app.log',
//        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
//        'max_files' => (int) ($_ENV['LOG_MAX_FILES'] ?? 5),
//    ],
//
//    // ================== CONFIGURATION CORS ==================
//    'cors' => [
//        'origin' => $_ENV['CORS_ORIGIN'] ?? '*',
//        'methods' => explode(',', $_ENV['CORS_METHODS'] ?? 'GET,POST,PUT,DELETE,OPTIONS'),
//        'headers' => explode(',', $_ENV['CORS_HEADERS'] ?? 'X-Requested-With,Content-Type,Accept,Origin,Authorization'),
//        'credentials' => (bool) ($_ENV['CORS_CREDENTIALS'] ?? false),
//    ],
//
//    // ================== CONFIGURATION JWT (future) ==================
//    'jwt' => [
//        'secret' => $_ENV['JWT_SECRET'] ?? 'your-secret-key',
//        'algorithm' => $_ENV['JWT_ALGORITHM'] ?? 'HS256',
//        'expiry' => (int) ($_ENV['JWT_EXPIRY'] ?? 3600), // 1 heure
//    ],
//
//    // ================== CONFIGURATION API ==================
//    'api' => [
//        'base_path' => $_ENV['API_BASE_PATH'] ?? '/api/v1',
//        'rate_limit' => [
//            'enabled' => (bool) ($_ENV['RATE_LIMIT_ENABLED'] ?? false),
//            'requests' => (int) ($_ENV['RATE_LIMIT_REQUESTS'] ?? 100),
//            'window' => (int) ($_ENV['RATE_LIMIT_WINDOW'] ?? 3600), // 1 heure
//        ],
//        'pagination' => [
//            'default_limit' => (int) ($_ENV['PAGINATION_DEFAULT_LIMIT'] ?? 20),
//            'max_limit' => (int) ($_ENV['PAGINATION_MAX_LIMIT'] ?? 100),
//        ],
//    ],
//
//    // ================== CONFIGURATION CACHE (future) ==================
//    'cache' => [
//        'enabled' => (bool) ($_ENV['CACHE_ENABLED'] ?? false),
//        'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
//        'path' => $_ENV['CACHE_PATH'] ?? __DIR__ . '/../var/cache',
//        'ttl' => (int) ($_ENV['CACHE_TTL'] ?? 3600),
//    ],
];