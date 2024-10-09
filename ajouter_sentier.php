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
    $nom_sentier = $_POST['nom_sentier'];
    $description = $_POST['description'];
    $difficulte = $_POST['difficulte'];
    $longueur_km = $_POST['longueur_km'];

    $stmt = $pdo->prepare("INSERT INTO Sentier (nom_sentier, description, difficulte, longueur_km) VALUES (:nom_sentier, :description, :difficulte, :longueur_km)");
    $stmt->execute([
        ':nom_sentier' => $nom_sentier,
        ':description' => $description,
        ':difficulte' => $difficulte,
        ':longueur_km' => $longueur_km
    ]);

    header("Location: dashboard.php"); 
    exit;
}
?>
