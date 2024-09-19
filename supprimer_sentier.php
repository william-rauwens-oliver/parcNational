<?php
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

if (isset($_GET['id'])) {
    $id_sentier = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Sentier WHERE id_sentier = :id");
    $stmt->execute([':id' => $id_sentier]);

    header("Location: dashboard.php");
    exit;
} else {
    echo "ID de sentier manquant.";
}
?>
