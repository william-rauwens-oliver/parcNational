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

    $stmt = $pdo->prepare("SELECT * FROM Sentier WHERE id_sentier = :id");
    $stmt->execute([':id' => $id_sentier]);
    $sentier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom_sentier = $_POST['nom_sentier'];
        $description = $_POST['description'];
        $difficulte = $_POST['difficulte'];
        $longueur_km = $_POST['longueur_km'];

        $stmt = $pdo->prepare("UPDATE Sentier SET nom_sentier = :nom, description = :description, difficulte = :difficulte, longueur_km = :longueur_km WHERE id_sentier = :id");
        $stmt->execute([
            ':nom' => $nom_sentier,
            ':description' => $description,
            ':difficulte' => $difficulte,
            ':longueur_km' => $longueur_km,
            ':id' => $id_sentier
        ]);

        header("Location: dashboard.php");
        exit;
    }
} else {
    echo "ID de sentier manquant.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Sentier</title>
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
        input, textarea, select {
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
    <h1>Modifier le Sentier</h1>
    <form action="" method="POST">
        <label for="nom_sentier">Nom :</label>
        <input type="text" id="nom_sentier" name="nom_sentier" value="<?= htmlspecialchars($sentier['nom_sentier']); ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($sentier['description']); ?></textarea>

        <label for="difficulte">Difficulté :</label>
        <select id="difficulte" name="difficulte" required>
            <option value="facile" <?= $sentier['difficulte'] == 'facile' ? 'selected' : ''; ?>>Facile</option>
            <option value="moyenne" <?= $sentier['difficulte'] == 'moyenne' ? 'selected' : ''; ?>>Moyenne</option>
            <option value="difficile" <?= $sentier['difficulte'] == 'difficile' ? 'selected' : ''; ?>>Difficile</option>
        </select>

        <label for="longueur_km">Longueur (km) :</label>
        <input type="number" id="longueur_km" name="longueur_km" step="0.1" value="<?= htmlspecialchars($sentier['longueur_km']); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
