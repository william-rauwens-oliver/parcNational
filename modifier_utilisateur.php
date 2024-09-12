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
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    $utilisateur = $stmt->fetch();

    if (!$utilisateur) {
        die("Utilisateur non trouvé.");
    }

    // Si le formulaire est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Mise à jour des informations de l'utilisateur
        $stmt = $pdo->prepare("UPDATE Utilisateur SET nom = ?, email = ?, role = ? WHERE id_utilisateur = ?");
        if ($stmt->execute([$nom, $email, $role, $id])) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    }
} else {
    die("ID utilisateur manquant.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Modifier utilisateur</h1>
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']); ?>" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']); ?>" required><br>

        <label for="role">Rôle :</label>
        <select name="role">
            <option value="admin" <?= $utilisateur['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="visiteur" <?= $utilisateur['role'] == 'visiteur' ? 'selected' : ''; ?>>Visiteur</option>
        </select><br>

        <input type="submit" value="Modifier">
    </form>
</body>
</html>
