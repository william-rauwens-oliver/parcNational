<?php
require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $id_ressource = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Ressource_Naturelle WHERE id_ressource = :id");
    $stmt->execute([':id' => $id_ressource]);
    $ressource = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $type = $_POST['type'];
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $quantite = $_POST['quantite'];

        $stmt = $pdo->prepare("UPDATE Ressource_Naturelle SET type = :type, nom = :nom, description = :description, quantite = :quantite WHERE id_ressource = :id");
        $stmt->execute([
            ':type' => $type,
            ':nom' => $nom,
            ':description' => $description,
            ':quantite' => $quantite,
            ':id' => $id_ressource
        ]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Ressource</title>
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
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Modifier Ressource</h1>
    <form action="" method="POST">
        <label for="type">Type :</label>
        <input type="text" id="type" name="type" value="<?= htmlspecialchars($ressource['type']); ?>" required>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($ressource['nom']); ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($ressource['description']); ?></textarea>

        <label for="quantite">Quantité :</label>
        <input type="number" id="quantite" name="quantite" value="<?= htmlspecialchars($ressource['quantite']); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
