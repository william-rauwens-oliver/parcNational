<?php
require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id_reservation = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM reservation_camping WHERE id_reservation = :id");
    $stmt->execute([':id' => $id_reservation]);

    header("Location: dashboard.php");
    exit;
} else {
    echo "ID de la réservation manquant.";
}
?>
