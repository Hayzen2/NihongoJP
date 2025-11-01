<?php

namespace App\Models;
use PDO;

class ProvincesModel {
    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }
    
    public function getProvincesByCountry($countryId) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM states WHERE country_id = :country_id");
        $stmt->execute([':country_id' => $countryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}