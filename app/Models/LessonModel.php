<?php

namespace App\Models;
use PDO;

class LessonModel {
    public function __construct($pdo = null) {
        $this->pdo = $pdo ?: (function() {
            require_once __DIR__ . '/../../config/setup_database.php';
            return getPDO();
        })();
    }
}