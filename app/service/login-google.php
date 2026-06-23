<?php
$rootPath = dirname(__DIR__, 2);
require_once $rootPath . '/app/config/session.php';

require_once __DIR__ . '/google-config.php';

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

error_log("[GOOGLE-LOGIN] session_id=" . session_id() . " | state_disimpan=" . $state);
error_log("[GOOGLE-LOGIN] redirect_uri=" . $google_config['redirect_uri']);
error_log("[GOOGLE-LOGIN] HTTP_HOST=" . ($_SERVER['HTTP_HOST'] ?? 'NOT SET'));
error_log("[GOOGLE-LOGIN] HTTPS=" . ($_SERVER['HTTPS'] ?? 'NOT SET'));
error_log("[GOOGLE-LOGIN] SERVER_PORT=" . ($_SERVER['SERVER_PORT'] ?? 'NOT SET'));

$params = [
    'client_id' => $google_config['client_id'],
    'redirect_uri' => $google_config['redirect_uri'],
    'scope' => implode(' ', $google_config['scopes']),
    'response_type' => $google_config['response_type'],
    'access_type' => $google_config['access_type'],
    'state' => $state,
    'prompt' => 'select_account'
];

$google_login_url = $google_config['auth_url'] . '?' . http_build_query($params);

error_log("[GOOGLE-LOGIN] auth_url=" . $google_login_url);

header('Location: ' . $google_login_url);
exit();
?>
