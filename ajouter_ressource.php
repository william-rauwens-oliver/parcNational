<?php
session_start();

$host = 'localhost';
$dbname = 'parcNational';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification que les données ont été envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $quantite = $_POST['quantite'];

    // Préparation et exécution de la requête SQL
    $stmt = $pdo->prepare("INSERT INTO RessourceNaturelle (type, nom, description, quantite) VALUES (:type, :nom, :description, :quantite)");
    $stmt->execute([
        ':type' => $type,
        ':nom' => $nom,
        ':description' => $description,
        ':quantite' => $quantite
    ]);

    header("Location: dashboard.php"); // Redirection vers le tableau de bord après ajout
    exit;
}
?>
