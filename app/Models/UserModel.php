<?php 

namespace App\Models;
use PHPMailer\PHPMailer\PHPMailer;
use PDO;

class UserModel {
    private $pdo;
    private $id;
    private $email;
    private $name;
    private $username;
    private $country;
    private $province;
    private $city;
    private $passwordHash;
    private $authProvider;
    private $createdAt;
    private $user_rank;
    private $avatar;
    public function __construct(
        $pdo = null,
        $id = null,
        $email = null,
        $name = null,
        $username = null,
        $country = null,
        $province = null,
        $city = null,
        $passwordHash = null,
        $authProvider = null,
        $createdAt = null,
        $user_rank = null,
        $avatar = null

    ) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->username = $username;
        $this->country = $country;
        $this->province = $province;
        $this->city = $city;
        $this->passwordHash = $passwordHash;
        $this->authProvider = $authProvider;
        $this->createdAt = $createdAt;
        $this->user_rank = $user_rank;
        $this->avatar = $avatar;
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            $pdo = getPDO();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $pdo;
        })();
    }
    //getters
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getName() { return $this->name; }
    public function getUsername() { return $this->username; }
    public function getCountry() { return $this->country; }
    public function getProvince() { return $this->province; }
    public function getCity() { return $this->city; }
    public function getPasswordHash() { return $this->passwordHash; }
    public function getAuthProvider() { return $this->authProvider; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUserRank() { return $this->user_rank; }
    public function getAvatar() { return $this->avatar; }

    //setters
    public function setId($id) { $this->id = $id; }
    public function setEmail($email) { $this->email = $email; }
    public function setName($name) { $this->name = $name; }
    public function setUsername($username) { $this->username = $username; }
    public function setCountry($country) { $this->country = $country; }
    public function setProvince($province) { $this->province = $province; }
    public function setCity($city) { $this->city = $city; }
    public function setPasswordHash($passwordHash) { $this->passwordHash = $passwordHash; }
    public function setAuthProvider($authProvider) { $this->authProvider = $authProvider; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
    public function setUserRank($user_rank) { $this->user_rank = $user_rank; }
    public function setAvatar($avatar) { $this->avatar = $avatar; }
    
    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLocalUserByEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email AND auth_provider = 'local'");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createUserLocal($email, $name, $passwordHash, $username, $country_id, $state_id, $city_id) {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, name, password_hash, auth_provider, username, country_id, state_id, city_id)
        VALUES (:email, :name, :password_hash, 'local', :username, :country_id, :state_id, :city_id)");
        $stmt->execute([':email' => $email, ':name' => $name, ':password_hash' => $passwordHash,
        ':username' => $username, ':country_id' => $country_id, ':state_id' => $state_id, ':city_id' => $city_id]);
        return $this->getUserByEmail($email); //return user obj
    }
    public function createUserGoogle($email, $name, $avatar) {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, name, avatar , auth_provider) VALUES (:email, :name, :avatar, 'google')");
        $stmt->execute([':email' => $email, ':name' => $name, ':avatar' => $avatar]);
        return $this->getUserByEmail($email); //return user obj
    }
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($password, $passwordHash) {
        return password_verify($password, $passwordHash);
    }

    public function generateAndSendOTP($email) {
        if(isset($_SESSION['otp-email'])) { // Check if OTP already generated, unset it
            unset($_SESSION['otp']);
            unset($_SESSION['otp-email']);
            unset($_SESSION['otp-expires-at']);
        }
        #generate 5 digit OTP
        $otp = random_int(10000, 99999);

        //Store OTP and expiry in session for 15 minutes
        $_SESSION['otp'] = $otp;
        $_SESSION['otp-email'] = $email;
        $_SESSION['otp-expires-at'] = time() + (15 * 60); // 15 minutes from now

        // Load email template and replace placeholders
        $template = file_get_contents(__DIR__ . '/../../resources/templates/OTP.html');
        $message = str_replace('{{otp}}', $otp, $template);
        $message = str_replace('{{name}}', $this->getUserByEmail($email)['name'], $message);

        
        //send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_EMAIL'];
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];

            //Recipients
            $mail->setFrom('no-reply@nihongojp.com', 'NihongoJP Support');
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = '[NihongoJP] Your Password Reset OTP';
            $mail->Body    = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function verifyOTP($inputOtp) {
        if (!isset($_SESSION['otp-email'])) { // Check if OTP was generated
            error_log("No OTP session found.");
            return;
        }

        $storedOtpData = $_SESSION['otp']; // Get stored OTP data
        if (time() > $_SESSION['otp-expires-at']) { // Check if OTP is expired
            unset($_SESSION['otp']);
            unset($_SESSION['otp-email']);
            unset($_SESSION['otp-expires-at']);
            error_log("OTP has expired.");
            return;
        }

        if ($inputOtp == $storedOtpData) { // Check if OTP matches, unset it
            $_SESSION['reset-email'] = $_SESSION['otp-email']; // Store email for password reset
            $_SESSION['reset-expiry'] = time() + (30 * 60); // 30 minutes to reset password
            unset($_SESSION['otp']);
            unset($_SESSION['otp-email']);
            unset($_SESSION['otp-expires-at']);
        }
    }
    public function updatePassword($email, $newPasswordHash) {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :password_hash WHERE email = :email");
        return $stmt->execute([':password_hash' => $newPasswordHash, ':email' => $email]);
    }
}