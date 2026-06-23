<?php
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/session.php';

unset($_SESSION['buyer_status']);
unset($_SESSION['buyer_email']);
unset($_SESSION['buyer_name']);
unset($_SESSION['buyer_google_id']);
unset($_SESSION['user_email']);
unset($_SESSION['user_name']);
unset($_SESSION['google_id']);

header("Location: /index.php");
exit();
?>
