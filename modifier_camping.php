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
        body { font-family: Arial, sans-serif; }
        form { max-width: 600px; margin: 20px auto; padding: 20px; background-color: #f4f4f9; }
        label { display: block; margin-bottom: 8px; }
        input { width: 100%; padding: 8px; margin-bottom: 20px; }
        button { padding: 10px; background-color: #007bff; color: #fff; border: none; cursor: pointer; }
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
        <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($camping['Image']); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
