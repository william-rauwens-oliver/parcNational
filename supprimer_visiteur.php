<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: gestion_visiteurs.php');
    exit;
}

$id_visiteur = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM Visiteur WHERE id_visiteur = ?");
$stmt->execute([$id_visiteur]);

header('Location: dashboard.php');
exit;
?>
