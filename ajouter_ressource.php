<?php
session_start();

require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $localisation = $_POST['localisation'];
    $date_observation = $_POST['date_observation'];
    $etat = $_POST['etat'];

    $stmt = $pdo->prepare("INSERT INTO Ressource_Naturelle (type, nom, description, localisation, date_observation, etat) 
                           VALUES (:type, :nom, :description, :localisation, :date_observation, :etat)");
    $stmt->execute([
        ':type' => $type,
        ':nom' => $nom,
        ':description' => $description,
        ':localisation' => $localisation,
        ':date_observation' => $date_observation,
        ':etat' => $etat
    ]);

    header("Location: dashboard.php");
    exit;
}
?>
