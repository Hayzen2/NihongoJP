<?php
require_once __DIR__ . '/../resources/helpers/render.php';

//simple routing
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Default route
if ($uri === '/' || $uri === '/home' || $uri === '/homepage') {
    render('homepage', [
    'style' => 'homepage',
    'script' => 'createSakura'
    ]);
} elseif ($uri === '/login') {
    render('login');
} elseif ($uri === '/register') {
    render('signup');
} else {
    // 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}
?>