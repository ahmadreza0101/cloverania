<?php
$rootPath = dirname(__DIR__, 2);

require_once $rootPath . '/vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists($rootPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($rootPath);
    $dotenv->load();
}

$currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$isLocalhost = strpos($currentHost, 'localhost') !== false || strpos($currentHost, '127.0.0.1') !== false;

if ($isLocalhost) {
    // For localhost, use the env value if available
    $envRedirectUri = $_ENV['GOOGLE_REDIRECT_URI'] ?? getenv('GOOGLE_REDIRECT_URI') ?? '';
    if (!empty($envRedirectUri)) {
        $redirectUri = $envRedirectUri;
        error_log("[GOOGLE-CONFIG] Localhost detected, using redirect_uri from env: " . $redirectUri);
    } else {
        // Fallback to dynamic if no env for localhost
        $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
        $protocol = $forwardedProto ?: ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http");
        $redirectUri = $protocol . '://' . $currentHost . '/app/proses/google/callback.php';
        error_log("[GOOGLE-CONFIG] Localhost detected, using dynamic redirect_uri: " . $redirectUri);
    }
} else {
    // For production (non-localhost), always use dynamic detection
    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
    if ($forwardedProto === 'https' || $forwardedProto === 'http') {
        $protocol = $forwardedProto;
    } else {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";
    }
    $redirectUri = $protocol . '://' . $currentHost . '/app/proses/google/callback.php';
    error_log("[GOOGLE-CONFIG] Production detected, using dynamic redirect_uri: " . $redirectUri);
    error_log("[GOOGLE-CONFIG] HTTP_X_FORWARDED_PROTO=" . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET'));
}

$google_config = [
    'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID') ?? '',
    'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET') ?? '',
    'redirect_uri' => $redirectUri,
    'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
    'token_url' => 'https://oauth2.googleapis.com/token',
    'user_info_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
    'scopes' => ['email', 'profile'],
    'response_type' => 'code',
    'access_type' => 'online'
];
?>
