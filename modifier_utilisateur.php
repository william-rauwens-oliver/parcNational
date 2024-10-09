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

    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$id]);
    $utilisateur = $stmt->fetch();

    if (!$utilisateur) {
        die("Utilisateur non trouvé.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $role = $_POST['role'];

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Modifier utilisateur</h1>
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']); ?>" required>

        <label for="email">Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']); ?>" required>

        <label for="role">Rôle :</label>
        <select name="role">
            <option value="admin" <?= $utilisateur['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="visiteur" <?= $utilisateur['role'] == 'visiteur' ? 'selected' : ''; ?>>Visiteur</option>
        </select>

        <input type="submit" value="Modifier">
    </form>
</body>
</html>
