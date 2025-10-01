<?php
require_once __DIR__ . '/../config/setup_database.php';
require_once __DIR__ . '/../vendor/autoload.php';

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
    '/lessons'      => [LessonController::class, 'showLessonLists'],
    '/exercises'    => [ExerciseController::class, 'showExerciseLists'],
    '/quizzes'      => [QuizController::class, 'showQuizLists'],
    '/rank'         => [RankController::class, 'showRankLists'],
    '/profile'      => [UserController::class, 'showProfile'],
    '/flashcards'    => [FlashcardController::class, 'showFlashcardList'],
    '/subscription' => [SubscriptionController::class, 'showSubscriptionPlans'],
    '/contact'      => [ContactController::class, 'showContactForm'],
    '/logout'       => [AuthController::class, 'logout'],
];

// Dispatch the request
if (isset($routes[$uri])) {
    [$controllerClass, $method] = $routes[$uri];
    $controller = new $controllerClass();
    $controller->$method();
} else {
    // 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
}
