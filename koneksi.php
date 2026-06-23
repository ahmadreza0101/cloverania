<?php
if (!isset($koneksi)) {
    require_once __DIR__ . '/vendor/autoload.php';

    if (file_exists(__DIR__ . '/.env')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    $tidb_config = [
        'host' => $_ENV['DB_TIDB_HOST'] ?? getenv('DB_TIDB_HOST') ?? '',
        'db'   => $_ENV['DB_TIDB_DB'] ?? getenv('DB_TIDB_DB') ?? '',
        'user' => $_ENV['DB_TIDB_USER'] ?? getenv('DB_TIDB_USER') ?? '',
        'pass' => $_ENV['DB_TIDB_PASS'] ?? getenv('DB_TIDB_PASS') ?? '',
        'port' => (int) ($_ENV['DB_TIDB_PORT'] ?? getenv('DB_TIDB_PORT') ?? 3306),
    ];

    $local_config = [
        'host' => 'localhost',
        'db'   => 'db_voidtype',
        'user' => 'root',
        'pass' => '',
        'port' => 3306
    ];

    if (!function_exists('connectTiDBWithSSL')) {
        function connectTiDBWithSSL(array $cfg)
        {
            $conn = mysqli_init();
            mysqli_ssl_set($conn, null, null, null, null, null);
            $success = @mysqli_real_connect(
                $conn,
                $cfg['host'],
                $cfg['user'],
                $cfg['pass'],
                $cfg['db'],
                $cfg['port'],
                null,
                MYSQLI_CLIENT_SSL
            );
            return $success ? $conn : false;
        }
    }

    $koneksi = null;
    if (!empty($tidb_config['host']) && !empty($tidb_config['db'])) {
        error_log("[DB] Attempting TiDB connection to: " . $tidb_config['host']);
        $koneksi = connectTiDBWithSSL($tidb_config);
        if ($koneksi) {
            error_log("[DB] TiDB connection successful");
        } else {
            error_log("[DB] TiDB connection failed");
        }
    }

    if (!$koneksi) {
        error_log("[DB] Attempting local connection");
        $koneksi = @mysqli_connect(
            $local_config['host'],
            $local_config['user'],
            $local_config['pass'],
            $local_config['db'],
            $local_config['port']
        );
        if ($koneksi) {
            error_log("[DB] Local connection successful");
        } else {
            error_log("[DB] Local connection failed: " . mysqli_connect_error());
        }
    }

    if (!$koneksi) {
        error_log("[DB] CRITICAL: All database connections failed");
        die("Koneksi database gagal. Pastikan environment variables DB_TIDB_HOST, DB_TIDB_DB, DB_TIDB_USER, DB_TIDB_PASS sudah diset di Vercel.");
    }

    mysqli_set_charset($koneksi, 'utf8mb4');
}

/** @var mysqli */