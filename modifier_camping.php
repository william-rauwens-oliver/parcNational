<?php
require 'database.php';

if (isset($_GET['id'])) {
    $id_camping = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Camping WHERE id_camping = :id");
    $stmt->execute([':id' => $id_camping]);
    $camping = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom_camping = $_POST['nom_camping'];
        $adresse_camping = $_POST['adresse_camping'];
        $nombre_personnes = $_POST['nombre_personnes'];
        $image_camping = $_POST['image_camping'];

        $stmt = $pdo->prepare("UPDATE Camping SET nom_camping = :nom, adresse_camping = :adresse, nombre_personnes = :nombre, image_camping = :image WHERE id_camping = :id");
        $stmt->execute([
            ':nom' => $nom_camping,
            ':adresse' => $adresse_camping,
            ':nombre' => $nombre_personnes,
            ':image' => $image_camping,
            ':id' => $id_camping
        ]);

        header("Location: dashboard.php");
        exit;
    }
} else {
    echo "ID de camping manquant.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Camping</title>
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
        input {
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
    <h1>Modifier le Camping</h1>
    <form action="" method="POST">
        <label for="nom_camping">Nom :</label>
        <input type="text" id="nom_camping" name="nom_camping" value="<?= htmlspecialchars($camping['nom_camping']); ?>" required>

        <label for="adresse_camping">Adresse :</label>
        <input type="text" id="adresse_camping" name="adresse_camping" value="<?= htmlspecialchars($camping['adresse_camping']); ?>" required>

        <label for="nombre_personnes">Capacité :</label>
        <input type="number" id="nombre_personnes" name="nombre_personnes" value="<?= htmlspecialchars($camping['nombre_personnes']); ?>" required>

        <label for="image_camping">URL de l'image :</label>
        <input type="url" id="image_camping" name="image_camping" value="<?= htmlspecialchars($camping['Image']); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
