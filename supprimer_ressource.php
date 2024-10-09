<?php
require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id_ressource = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Ressource_Naturelle WHERE id_ressource = :id");
    $stmt->execute([':id' => $id_ressource]);

    header("Location: dashboard.php");
    exit;
} else {
    echo "ID de la ressource manquant.";
}
?>
