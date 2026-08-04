<?php

// Define necessary constants
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap minimal CodeIgniter to get database connection
$pathsConfig = new \Config\Paths();
$bootstrap = \CodeIgniter\Boot::bootWeb($pathsConfig);

$db = \Config\Database::connect();

echo "=== Verifying College Data ===\n\n";

$colleges = $db->table('tb_colleges')->get()->getResultArray();

echo "Total colleges: " . count($colleges) . "\n\n";

foreach ($colleges as $college) {
    echo "ID: {$college['id']}\n";
    echo "Arabic Name: {$college['college_name_ar']}\n";
    echo "English Name: {$college['college_name_en']}\n";
    echo "Code: {$college['college_code']}\n";
    echo "Active: {$college['active']}\n";
    echo "Created: {$college['created_at']}\n";
    echo "Updated: {$college['updated_at']}\n";
    echo str_repeat("-", 50) . "\n\n";
}

echo "=== Verification Complete ===\n";
