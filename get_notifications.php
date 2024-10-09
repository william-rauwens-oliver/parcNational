<?php

header('Content-Type: application/json');

require 'config/database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT titre, message, date_envoi FROM Notification WHERE id_utilisateur IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion ou de requête : ' . $e->getMessage()]);
}
?>