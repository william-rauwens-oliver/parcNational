<?php
$host = "localhost";
$dbname = "parcNational";
$username = "root";
$password = "root"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des sentiers
    $sentiers = [];
    $sql = "SELECT nom_sentier, description, difficulte, longueur_km, points_interet, image, ville, pays FROM Sentier";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des réservations de camping
    $reservation_camping = [];
    $sql = "SELECT date_reservation, date_debut, date_fin, statut, nombre_personnes, nom_camping, image FROM reservation_camping";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reservation_camping = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}
?>
