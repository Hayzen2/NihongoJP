<?php 

namespace App\Models;
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
        $createdAt = null
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
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            $pdo = getPDO();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $pdo;
        })();
    }
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
    public function createUserLocal($email, $name, $passwordHash, $username, $country_id, $state_id, $city_id) {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, name, password_hash, auth_provider, username, country_id, state_id, city_id)
        VALUES (:email, :name, :password_hash, 'local', :username, :country_id, :state_id, :city_id)");
        $stmt->execute([':email' => $email, ':name' => $name, ':password_hash' => $passwordHash,
        ':username' => $username, ':country_id' => $country_id, ':state_id' => $state_id, ':city_id' => $city_id]);
        return $this->pdo->lastInsertId();
    }
    public function createUserGoogle($email, $name) {
        $stmt = $this->pdo->prepare("INSERT INTO users (email, name, auth_provider) VALUES (:email, :name, 'google')");
        $stmt->execute([':email' => $email, ':name' => $name]);
        return $this->pdo->lastInsertId();
    }
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($password, $passwordHash) {
        return password_verify($password, $passwordHash);
    }
}