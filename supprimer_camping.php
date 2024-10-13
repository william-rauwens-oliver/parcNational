<?php
require 'database.php';

if (isset($_GET['id'])) {
    $id_camping = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Camping WHERE id_camping = :id");
    $stmt->execute([':id' => $id_camping]);

    header("Location: dashboard.php");
    exit;
} else {
    echo "ID de camping manquant.";
}
?>
