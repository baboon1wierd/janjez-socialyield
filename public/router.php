<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

$projectRootPath = __DIR__ . '/../' . $uri;
if ($uri !== '/' && file_exists($projectRootPath) && is_file($projectRootPath)) {
    $ext = pathinfo($projectRootPath, PATHINFO_EXTENSION);
    $types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];
    if (isset($types[$ext])) {
        header('Content-Type: ' . $types[$ext]);
    }
    readfile($projectRootPath);
    return;
}

if ($uri === '/' && file_exists(__DIR__ . '/../index.html')) {
    readfile(__DIR__ . '/../index.html');
    return;
}

if ($uri === '/api' || $uri === '/api/' || str_starts_with($uri, '/api/')) {
    require __DIR__ . '/../api/index.php';
    return;
}

require __DIR__ . '/index.php';
