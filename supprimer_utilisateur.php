<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: SignIn.php');
    exit();
}

require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Utilisateur WHERE id_utilisateur = ?");
    if ($stmt->execute([$id])) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    die("ID utilisateur manquant.");
}
