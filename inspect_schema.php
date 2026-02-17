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
    
    $tables = ['tb_user_item_progress'];
    
    foreach ($tables as $table) {
        echo "Table: $table\n";
        $stmt = $pdo->query("SHOW CREATE TABLE $table");
        $row = $stmt->fetch();
        echo $row['Create Table'] . "\n\n";
    }

} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
