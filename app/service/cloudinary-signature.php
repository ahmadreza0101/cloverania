<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
header('Content-Type: application/json');

$rootPath = dirname(__DIR__, 2);
require_once $rootPath . '/vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists($rootPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($rootPath);
    $dotenv->load();
}

$cloudName = trim($_ENV['CLOUDINARY_CLOUD_NAME'] ?? getenv('CLOUDINARY_CLOUD_NAME') ?? '');
$apiKey    = trim($_ENV['CLOUDINARY_API_KEY'] ?? getenv('CLOUDINARY_API_KEY') ?? '');
$apiSecret = trim($_ENV['CLOUDINARY_API_SECRET'] ?? getenv('CLOUDINARY_API_SECRET') ?? '');

if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
    http_response_code(500);
    echo json_encode(['error' => 'Cloudinary credentials tidak lengkap di .env']);
    exit();
}

$resourceType = (($_GET['type'] ?? 'image') === 'raw') ? 'raw' : 'image';

$timestamp = time();
$folder    = 'voidtype_store';

$paramsToSign = [
    'folder'    => $folder,
    'timestamp' => $timestamp,
];
ksort($paramsToSign);

$paramString = '';
foreach ($paramsToSign as $key => $value) {
    $paramString .= ($paramString === '' ? '' : '&') . $key . '=' . $value;
}
$signature = sha1($paramString . $apiSecret);

echo json_encode([
    'cloudName'    => $cloudName,
    'apiKey'       => $apiKey,
    'timestamp'    => $timestamp,
    'signature'    => $signature,
    'folder'       => $folder,
    'resourceType' => $resourceType,
]);
