<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: SignIn.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'parcNational';
$username = 'root';  // Adapter si nécessaire
$password = 'root';  // Adapter si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Gestion des utilisateurs
$stmt = $pdo->query("SELECT * FROM Utilisateur");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion des sentiers
$stmt = $pdo->query("SELECT * FROM Sentier");
$sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Utilisateurs et Sentiers</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Tableau de bord administrateur</h1>
    
    <h2>Gestion des utilisateurs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($utilisateurs as $utilisateur) : ?>
        <tr>
            <td><?= htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
            <td><?= htmlspecialchars($utilisateur['nom']); ?></td>
            <td><?= htmlspecialchars($utilisateur['email']); ?></td>
            <td><?= htmlspecialchars($utilisateur['role']); ?></td>
            <td>
                <a href="modifier_utilisateur.php?id=<?= $utilisateur['id_utilisateur']; ?>">Modifier</a>
                <a href="supprimer_utilisateur.php?id=<?= $utilisateur['id_utilisateur']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Gestion des sentiers</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Difficulté</th>
            <th>Longueur (km)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($sentiers as $sentier) : ?>
        <tr>
            <td><?= htmlspecialchars($sentier['id_sentier']); ?></td>
            <td><?= htmlspecialchars($sentier['nom_sentier']); ?></td>
            <td><?= htmlspecialchars($sentier['description']); ?></td>
            <td><?= htmlspecialchars($sentier['difficulte']); ?></td>
            <td><?= htmlspecialchars($sentier['longueur_km']); ?></td>
            <td>
                <a href="modifier_sentier.php?id=<?= $sentier['id_sentier']; ?>">Modifier</a>
                <a href="supprimer_sentier.php?id=<?= $sentier['id_sentier']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sentier ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
