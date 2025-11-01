<?php

namespace App\Models;

use PDO;

class CountriesModel
{
    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }

    public function getCountries() {
        $stmt = $this->pdo->prepare("SELECT id, name FROM countries");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}