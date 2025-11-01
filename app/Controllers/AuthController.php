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
        public function showLoginForm() {
            render('preLoggedIn/login', [
                'styles' => ['auth/login'],
                'scripts' => ['handleCredentials']
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
                        $userId = $this->userModel->createUserGoogle($email, $name);
                    } else{
                        $userId = $user['id'];
                    }
                    $_SESSION['user_id'] = $userId;
                    
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
                        $_SESSION['user_id'] = $user['id'];
                        header("Location: /");
                        exit;
                    } else {
                        http_response_code(401);
                        echo 'Invalid login credentials.';
                    }
                } else {
                    http_response_code(401);
                    echo 'Invalid login credentials.';
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
                    http_response_code(409);
                    alert('User already exists.');
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $userId = $this->userModel->createUserLocal($email, $name, $passwordHash, $username, $country_id, $province_id, $city_id);
                    $_SESSION['user_id'] = $userId;
                    header("Location: /");
                    exit;
                }
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
        public function checkUsernameAvailability($username) {
            $user = $this->userModel->getUserByUsername($username);
            return $user ? false : true;
        }
        public function logout() {
            session_start();
            session_unset();
            session_destroy();
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

        public function handleForgotPassword() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = $_POST['email'] ?? '';
                $user = $this->userModel->getUserByEmail($email);
                if($user) {
                    #generate 5 digit OTP
                    $otp = random_int(10000, 99999);

                    //Store OTP in session for 5 minutes
                    $_SESSION['password_reset_otp'] = $otp;
                    $_SESSION['password_reset_email'] = $email;
                    $_SESSION['otp_expiry'] = time() + 300; // 5 minutes from now
                    //Send OTP via email
                    $to = $email;
                    $subject = "NihongoJP Password Reset OTP";
                    $headers = "From: no-reply@nihongojp.com\r\n";
                    

                    
                    header("Location: /reset-password");
                } else {
                    http_response_code(404);
                    return 'No email found for that account.';
                }
            }
        }
}
?>