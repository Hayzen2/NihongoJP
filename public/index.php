<?php

require_once __DIR__ . '/../config/setup_database.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../resources/helpers/render.php';

use App\Controllers\HomepageController;
use App\Controllers\AuthController;
use App\Controllers\LessonController;
use App\Controllers\ExerciseController;
use App\Controllers\QuizController;
use App\Controllers\RankController;
use App\Controllers\UserController;
use App\Controllers\FlashcardController;
use App\Controllers\SubscriptionController;
use App\Controllers\ContactController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the request path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Redirect /home and /homepage to /
if ($uri === '/home' || $uri === '/homepage') {
    header("Location: /", true, 301);
    exit;
}

$routes = [
    '/'             => [HomepageController::class, 'showHomepage'],
    '/login'        => [AuthController::class, 'showLoginForm'],
    '/register'     => [AuthController::class, 'showRegisterForm'],
    '/login/local'  => [AuthController::class, 'handleLocalLogin'],
    '/login/google' => [AuthController::class, 'handleGoogleLogin'],
    '/register/local' => [AuthController::class, 'handleLocalRegister'],
    '/register/google' => [AuthController::class, 'handleGoogleLogin'],
    '/login/forgot-password' => [AuthController::class, 'showForgotPasswordForm'],
    '/lessons'      => [LessonController::class, 'showLessonLists'],
    '/exercises'    => [ExerciseController::class, 'showExerciseLists'],
    '/quizzes'      => [QuizController::class, 'showQuizLists'],
    '/rank'         => [RankController::class, 'showRankLists'],
    '/profile'      => [UserController::class, 'showProfile'],
    '/flashcards'    => [FlashcardController::class, 'showFlashcardList'],
    '/flashcards/view/:id' => [FlashcardController::class, 'showFlashcardContent'],
    '/flashcards/new' => [FlashcardController::class, 'createNewFlashcard'],
    '/flashcards/edit/:id' => [FlashcardController::class, 'showEditFlashcardForm'],
    '/flashcards/delete/:id' => [FlashcardController::class, 'deleteFlashcard'],
    '/subscription' => [SubscriptionController::class, 'showSubscriptionPlans'],
    '/contact'      => [ContactController::class, 'showContactForm'],
    '/logout'       => [AuthController::class, 'logout'],
    '/api/countries' => [AuthController::class, 'getCountries'],
    '/api/provinces/:countryId' => [AuthController::class, 'getProvincesByCountry'],
    '/api/cities/:provinceId' => [AuthController::class, 'getCitiesByProvince'],
    '/check-username/:username' => [AuthController::class, 'checkUsernameAvailability'],
];

$found = false;
$publicRoutes = ['/login', '/register',
                '/register/local', '/register/google', '/login/google',
                '/login/forgot-password', '/login/local', '/contact',
                '/api/countries', '/api/provinces/:countryId',
                '/api/cities/:provinceId'];

$isPublic = false;
foreach ($publicRoutes as $route) {
    if (preg_match('#^' . preg_replace('#:[^/]+#', '[^/]+', $route) . '$#', $uri)) {
        $isPublic = true;
        break;
    }
}

if (!isset($_SESSION['user_id']) && !$isPublic) {
    header("Location: /login");
    exit;
} elseif (isset($_SESSION['user_id']) && in_array($uri, ['/login', '/register'])) {
    header("Location: /");
    exit;
}
foreach ($routes as $path => [$controllerClass, $method]) {
    // Convert /flashcards/view/:id → regex with capture group
    $pattern = preg_replace('#:[^/]+#', '(\d+)', $path);
    $pattern = "#^" . $pattern . "$#";

    if (preg_match($pattern, $uri, $matches)) {
        $controller = new $controllerClass();

        // Remove first element (full match) -> leave only parameters
        array_shift($matches);

        // Call controller with params (like $id)
        $controller->$method(...$matches);
        $found = true;
        break;
    }
}

if (!$found) {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}
