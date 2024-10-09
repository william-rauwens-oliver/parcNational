<?php
$host = 'localhost';
$dbname = 'parcNational';
$username = 'root';
$password = 'root';

// Connexion avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion avec PDO : " . $e->getMessage());
}

// Connexion avec MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion avec MySQLi : " . $conn->connect_error);
}
?>
