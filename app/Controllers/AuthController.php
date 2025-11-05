<?php
    namespace App\Controllers;
    use Google_Client;
    use App\Models\UserModel;
    use App\Models\CountriesModel;
    use App\Models\ProvincesModel;
    use App\Models\CitiesModel;
    class AuthController {
        private $userModel;
        private $countriesModel;
        private $provincesModel;
        private $citiesModel;
        public function __construct() {
            $this->userModel = new UserModel();
            $this->countriesModel = new CountriesModel();
            $this->provincesModel = new ProvincesModel();
            $this->citiesModel = new CitiesModel();
        }
        public function showPrivacyPolicy() {
            render('privacy-policy', [
                'styles' => ['privacy-policy'],
                'scripts' => []
            ]);
        }
        public function showLoginForm() {
            render('preLoggedIn/login', [
                'styles' => ['auth/login'],
                'scripts' => ['handleCredentials'],
            ]);
        }
        public function handleGoogleLogin() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $token = $data['token'] ?? null;
                if (!$token) {
                    http_response_code(400);
                    echo 'ID token is required.';
                    return;
                }
                $client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);
                $payload = $client->verifyIdToken($token);
                if ($payload) {
                    $email = $payload['email'];
                    $name = $payload['name'];
                    $user = $this->userModel->getUserByEmail($email);
                    if (!$user) {
                        $user= $this->userModel->createUserGoogle($email, $name);
                    } else{
                        $user = $this->userModel->getUserByEmail($email);
                    }
                    $_SESSION['user'] = $user;
                    header("Location: /");
                    exit;
                    
                } else {
                    http_response_code(401);
                    echo 'Invalid ID token.';
                }
            } else {
                http_response_code(405);
                echo 'Method Not Allowed';
            }
        }

        public function handleLocalLogin() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $user = $this->userModel->getUserByUsername($username);
                if($user) {
                    if($this->userModel->verifyPassword($password, $user['password_hash'])) {
                        $_SESSION['user'] = $user;
                        header("Location: /");
                        exit;
                    } else {
                        $error = 'Invalid username or password.';
                        render('preLoggedIn/login', [
                            'styles' => ['auth/login'],
                            'scripts' => ['handleCredentials'],
                            'error' => $error
                        ]);
                    }
                } else {
                    $error = 'Invalid username or password.';
                    render('preLoggedIn/login', [
                        'styles' => ['auth/login'],
                        'scripts' => ['handleCredentials'],
                        'error' => $error
                    ]);
                }
            }
        }

        public function checkEmailAvailability() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $user = $this->userModel->getUserByEmail($email);
                if($user) {
                    if($user['auth_provider'] == 'google') {
                        echo json_encode(['exists' => true , 'message' => 'This email is already registered with Google. Please login with Google.']);
                    } else{
                        echo json_encode(['exists' => true, 'message' => 'This email is already taken. Please choose a different email.']);
                    }
                } else {
                    echo json_encode(['exists' => false]);
                }
            }
        }
        public function checkUsernameAvailability() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'] ?? '';
                $user = $this->userModel->getUserByUsername($username);
                if($user) {
                    echo json_encode(['exists' => true, 'message' => 'This username is already taken. Please choose a different username.']);
                } else {
                    echo json_encode(['exists' => false]);
                }
            }
        }
        public function handleLocalRegister() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $name = $_POST['name'] ?? '';
                $username = $_POST['username'] ?? '';
                $country_id = $_POST['country'] ?? '';
                $province_id = $_POST['province'] ?? '';
                $city_id = $_POST['city'] ?? '';
                $password = $_POST['password'] ?? '';
                $user = $this->userModel->getUserByEmail($email);
                if($user) {
                    http_response_code(400);
                    echo 'Email already exists.';
                    return;
                } else{
                    $user = $this->userModel->getUserByUsername($username);
                    if($user) {
                        http_response_code(400);
                        echo 'Username already exists.';
                        return;
                    }
                }
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $user = $this->userModel->createUserLocal($email, $name, $passwordHash, $username, $country_id, $province_id, $city_id);
                $_SESSION['user'] = $user;
                header("Location: /");
                exit;
            }
        }

        public function showRegisterForm() {
            render('preLoggedIn/register', [
                'styles' => ['auth/register'],
                'scripts' => ['handleCredentials', 'pickDestination']
            ]);
        }

        public function showForgotPasswordForm() {
            render('preLoggedIn/forgotPassword', [
                'styles' => ['auth/forgotPassword'],
                'scripts' => []
            ]);
        }
        public function logout() {
            session_start();
            session_unset();
            session_destroy();
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Location: /login");
            exit;
        }

        public function getCountries() {
            header('Content-Type: application/json');
            echo json_encode($this->countriesModel->getCountries());
            exit;
        }

        public function getProvincesByCountry($countryId) {
            header('Content-Type: application/json');
            echo json_encode($this->provincesModel->getProvincesByCountry($countryId));
            exit;
        }

        public function getCitiesByProvince($provinceId) {
            header('Content-Type: application/json');
            echo json_encode($this->citiesModel->getCitiesByProvince($provinceId));
            exit;
        }

        public function sendPasswordResetOTP() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $user = $this->userModel->getLocalUserByEmail($email);
                if($user) {
                    $this->userModel->generateAndSendOTP($email);
                    header("Location: /login/forgot-password/input-otp");
                    exit;
                } else {
                    http_response_code(404);
                    return 'No email found for that account.';
                }
            }
        }
        public function showVerifyOTPForm() {
            $email = $_GET['email'] ?? '';
            render('preLoggedIn/inputOTP', [
                'styles' => ['auth/inputOTP', 'auth/forgotPassword'],
                'scripts' => ['handleCredentials'],
                'email' => $email
            ]);
        }
        public function verifyPasswordResetOTP() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_SESSION['otp-email'] ?? '';
                if(!$email) {
                    header("Location: /login/forgot-password/reset-expired");
                    exit;
                }
                $otpParts = [
                    $_POST['otp1'] ?? '',
                    $_POST['otp2'] ?? '',
                    $_POST['otp3'] ?? '',
                    $_POST['otp4'] ?? '',
                    $_POST['otp5'] ?? ''
                ];
                $otp = implode('', $otpParts); // Combine parts to form full OTP
                $this->userModel->verifyOTP($otp, $email);
                header("Location: /login/forgot-password/reset-password-form");
                exit;
            }
        }
        public function showResetPasswordForm() {
            render('preLoggedIn/resetPassword', [
                'styles' => ['auth/forgotPassword'],
                'scripts' => ['handleCredentials']
            ]);
        }
        public function resetPassword() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_SESSION['reset-email'] ?? '';
                $expiry = $_SESSION['reset-expiry'] ?? '';
                if(time() > $expiry) {
                    unset($_SESSION['reset-email']);
                    unset($_SESSION['reset-expiry']);
                    header("Location: /login/forgot-password/reset-expired");
                    exit;
                }
                if(!$email) {
                    header("Location: /login/forgot-password/reset-expired");
                    exit;
                }
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                if($newPassword !== $confirmPassword) {
                    http_response_code(400);
                    return 'Passwords do not match.';
                }
                
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->userModel->updatePasswordByEmail($email, $passwordHash);
                // Destroy session
                unset($_SESSION['reset-email']);
                unset($_SESSION['reset-expiry']);
                header("Location: /login");
                exit;
            }
        }
        public function showExpiredTokenOrOTPPage() {
            render('preLoggedIn/resetExpired', [
                'styles' => ['auth/forgotPassword'],
                'scripts' => ['handleCredentials']
            ]);
        }
    }
?>