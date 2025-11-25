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
use App\Controllers\BookController;
use Dotenv\Dotenv; // Load environment variables like GOOGLE_CLIENT_ID

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
    '/privacy' => [AuthController::class, 'showPrivacyPolicy'],
    '/login'        => [AuthController::class, 'showLoginForm'],
    '/register'     => [AuthController::class, 'showRegisterForm'],
    '/login/local'  => [AuthController::class, 'handleLocalLogin'],
    '/login/google' => [AuthController::class, 'handleGoogleLogin'],
    '/register/local' => [AuthController::class, 'handleLocalRegister'],
    '/login/forgot-password' => [AuthController::class, 'showForgotPasswordForm'],
    '/login/forgot-password/send-otp' => [AuthController::class, 'sendPasswordResetOTP'],
    '/login/forgot-password/input-otp' => [AuthController::class, 'showVerifyOTPForm'],
    '/login/forgot-password/verify-otp' => [AuthController::class, 'verifyPasswordResetOTP'],
    '/login/forgot-password/reset-password-form' => [AuthController::class, 'showResetPasswordForm'],
    '/login/forgot-password/reset-password' => [AuthController::class, 'resetPassword'],
    '/login/forgot-password/reset-expired' => [AuthController::class, 'showExpiredTokenOrOTPPage'],
    '/check-username' => [AuthController::class, 'checkUsernameAvailability'],
    '/check-email' => [AuthController::class, 'checkEmailAvailability'],
    '/lessons'      => [LessonController::class, 'showLessonChoices'],
    '/lessons/:level/kanji'      => [LessonController::class, 'showLessonsByLevelKanji'],
    '/lessons/:level/vocabulary'      => [LessonController::class, 'showLessonsByLevelVocabulary'],
    '/lessons/:level/grammar'      => [LessonController::class, 'showLessonsByLevelGrammar'],
    '/lessons/:level/grammar/:title'      => [LessonController::class, 'showLessonsContentByTitleGrammar'],
    '/buy-books'    => [BookController::class, 'showBooks'],
    '/quizzes'      => [QuizController::class, 'showQuizLists'],
    '/rank'         => [RankController::class, 'showRankLists'],
    '/profile'      => [UserController::class, 'showProfile'],
    '/flashcards'    => [FlashcardController::class, 'showFlashcardList'],
    '/flashcards/view/:id' => [FlashcardController::class, 'showFlashcardContent'],
    '/flashcards/new' => [FlashcardController::class, 'createNewFlashcard'],
    '/flashcards/edit/:id' => [FlashcardController::class, 'showEditFlashcardForm'],
    '/flashcards/delete/:id' => [FlashcardController::class, 'deleteFlashcard'],
    '/subscription' => [SubscriptionController::class, 'showSubscriptionPlans'],
    '/logout'       => [AuthController::class, 'logout'],
    '/api/countries' => [AuthController::class, 'getCountries'],
    '/api/provinces/:countryId' => [AuthController::class, 'getProvincesByCountry'],
    '/api/cities/:provinceId' => [AuthController::class, 'getCitiesByProvince'],
];

$found = false;
$publicRoutes = ['/login', '/register',
                '/register/local', '/login/google',
                '/login/forgot-password', '/login/local', '/contact',
                '/api/countries', '/api/provinces/:countryId',
                '/api/cities/:provinceId', '/login/forgot-password/send-otp',
                '/login/forgot-password/input-otp',
                '/login/forgot-password/verify-otp', '/login/forgot-password/reset-password-form',
                '/login/forgot-password/reset-expired', '/check-username', '/check-email'];

$isPublic = false;
foreach ($publicRoutes as $route) {
    if (preg_match('#^' . preg_replace('#:[^/]+#', '[^/]+', $route) . '$#', $uri)) {
        $isPublic = true;
        break;
    }
}

if (!isset($_SESSION['user']) && !$isPublic) {
    header("Location: /login");
    exit;
} elseif (isset($_SESSION['user']) && in_array($uri, ['/login', '/register'])) {
    header("Location: /");
    exit;
}
foreach ($routes as $path => [$controllerClass, $method]) {
    // Convert /flashcards/view/:id → regex with capture group
    $pattern = preg_replace('#:[^/]+#', '([^/]+)', $path);
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
