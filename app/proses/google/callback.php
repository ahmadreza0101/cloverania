<?php
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/session.php';

require_once $rootPath . '/app/service/google-config.php';
include $rootPath . '/koneksi.php';
/** @var mysqli $koneksi */

if (isset($_GET['code'])) {
    error_log("[GOOGLE-CALLBACK] masuk dengan code | session_id=" . session_id());
    error_log("[GOOGLE-CALLBACK] state_dari_google=" . ($_GET['state'] ?? 'TIDAK ADA'));
    error_log("[GOOGLE-CALLBACK] state_di_session=" . ($_SESSION['oauth_state'] ?? 'TIDAK ADA'));

    if (
        !isset($_GET['state']) ||
        !isset($_SESSION['oauth_state']) ||
        $_GET['state'] !== $_SESSION['oauth_state']
    ) {
        error_log("[GOOGLE-CALLBACK] GAGAL di validasi state, redirect ke login_gagal");
        header("Location: /index.php?pesan=login_gagal");
        exit();
    }
    unset($_SESSION['oauth_state']);
    error_log("[GOOGLE-CALLBACK] state valid, lanjut fetch token");

    $token_params = [
        'code' => $_GET['code'],
        'client_id' => isset($google_config['client_id']) ? $google_config['client_id'] : '',
        'client_secret' => isset($google_config['client_secret']) ? $google_config['client_secret'] : '',
        'redirect_uri' => isset($google_config['redirect_uri']) ? $google_config['redirect_uri'] : '',
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init(isset($google_config['token_url']) ? $google_config['token_url'] : '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $token = json_decode($response, true);
    error_log("[GOOGLE-CALLBACK] hasil token=" . json_encode($token));

    if (isset($token['error']) || !isset($token['access_token'])) {
        error_log("[GOOGLE-CALLBACK] GAGAL fetch token, redirect ke login_gagal");
        header("Location: /index.php?pesan=login_gagal");
        exit();
    }

    // Get user info using access token
    $ch = curl_init(isset($google_config['user_info_url']) ? $google_config['user_info_url'] : '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token['access_token']
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $user_response = curl_exec($ch);
    curl_close($ch);
    
    $google_account_info = json_decode($user_response, true);

    $email = $google_account_info['email'];
    $name  = $google_account_info['name'];
    $google_id = $google_account_info['sub'];

    error_log("[GOOGLE-CALLBACK] berhasil ambil profil: email=$email name=$name id=$google_id");

    if (!isset($koneksi)) {
        die("Database connection failed: Unable to access database connection.");
    }
    $checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'tb_auth'");
    if (mysqli_num_rows($checkTable) == 0) {
        $createTableSql = "
            CREATE TABLE IF NOT EXISTS tb_auth (
                google_id VARCHAR(255) PRIMARY KEY,
                buyer_email VARCHAR(100) NOT NULL UNIQUE,
                registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ";
        mysqli_query($koneksi, $createTableSql);
    }

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_auth WHERE google_id = ? OR buyer_email = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $google_id, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $check = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$check) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_auth (google_id, buyer_email) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $google_id, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        error_log("[GOOGLE-CALLBACK] user baru di-insert ke tb_auth");
    } else {
        error_log("[GOOGLE-CALLBACK] user sudah ada di tb_auth");
    }

    $_SESSION['buyer_status']   = "login";
    $_SESSION['buyer_email']    = $email;
    $_SESSION['buyer_name']     = $name;
    $_SESSION['buyer_google_id'] = $google_id;

    $_SESSION['user_email']     = $email;
    $_SESSION['user_name']      = $name;
    $_SESSION['google_id']      = $google_id;

    error_log("[GOOGLE-CALLBACK] SESSION SET, buyer_status=" . $_SESSION['buyer_status'] . " | session_id=" . session_id());

    header("Location: /index.php");
    exit();
} else {
    error_log("[GOOGLE-CALLBACK] tidak ada code di GET, redirect ke login_gagal. GET params=" . json_encode($_GET));
    header("Location: /index.php?pesan=login_gagal");
    exit();
}
?>
