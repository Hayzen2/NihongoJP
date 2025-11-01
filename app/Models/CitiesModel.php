<?php

namespace App\Models;
use Exception;
class CitiesModel {
    private string $jsonPath;
    public function __construct($jsonPath = null) {
        $this->jsonPath = $jsonPath ?: __DIR__ . '/../../config/jsons/cities.json';
    }

    public function getCitiesByProvince($provinceId) {
        if(!file_exists($this->jsonPath)) {
            throw new Exception('Cities JSON file not found');
        }

        $cities = json_decode(file_get_contents($this->jsonPath), true); // Convert JSON string to array
        return array_values(array_filter($cities, function($c) use ($provinceId) {
            return $c['state_id'] == $provinceId;
        })); // Filter cities by province
    }
}