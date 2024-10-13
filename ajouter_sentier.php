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
    $points_interet = $_POST['points_interet'];
    $image = $_POST['image']; // Nouvelle ligne pour récupérer l'URL de l'image

    // Ajoutez les colonnes 'points_interet' et 'image' à la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO Sentier (nom_sentier, description, difficulte, longueur_km, points_interet, image) VALUES (:nom_sentier, :description, :difficulte, :longueur_km, :points_interet, :image)");
    $stmt->execute([
        ':nom_sentier' => $nom_sentier,
        ':description' => $description,
        ':difficulte' => $difficulte,
        ':longueur_km' => $longueur_km,
        ':points_interet' => $points_interet, // Nouvelle ligne pour points d'intérêt
        ':image' => $image // Nouvelle ligne pour l'image
    ]);

    header("Location: dashboard.php"); 
    exit;
}
?>
