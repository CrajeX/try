<?php
// Database connection settings (example)
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "job-board";
$servername = "sql12743646";
$username = "sql12743646";
$password = "";
$dbname = "sql12743646";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
