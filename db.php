<?php
// db.php - Database connection file

$host = 'localhost';           // or your server's hostname
$dbname = 'statusapp';         // your database name
$username = 'root';   // replace with your DB username
$password = 'jeffld';   // replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
