<?php
$host = 'localhost';
$db   = 'webeasystep';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Rename enrollment_id to course_enrollment_id in tb_user_item_progress
    echo "Attempting to rename enrollment_id to course_enrollment_id in tb_user_item_progress...\n";
    try {
        $pdo->exec("ALTER TABLE tb_user_item_progress CHANGE enrollment_id course_enrollment_id INT UNSIGNED NULL DEFAULT NULL");
        echo " - Success: Column renamed.\n";
    } catch (\PDOException $e) {
        if (strpos($e->getMessage(), "Unknown column 'enrollment_id'") !== false) {
             echo " - Skipped: 'enrollment_id' not found (maybe already renamed?).\n";
        } else {
             echo " - Error: " . $e->getMessage() . "\n";
        }
    }

    // 2. Drop tb_unit_enrollments
    echo "\nAttempting to drop tb_unit_enrollments...\n";
    try {
        $pdo->exec("DROP TABLE IF EXISTS tb_unit_enrollments");
        echo " - Success: Table dropped (or didn't exist).\n";
    } catch (\PDOException $e) {
        echo " - Error: " . $e->getMessage() . "\n";
    }
    
    echo "\nDatabase fix completed.\n";

} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
