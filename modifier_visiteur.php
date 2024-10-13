<?php
require 'database.php';

if (!isset($_GET['id'])) {
    header('Location: gestion_visiteurs.php');
    exit;
}

$id_visiteur = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM Visiteur WHERE id_visiteur = ?");
$stmt->execute([$id_visiteur]);
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visiteur) {
    header('Location: gestion_visiteurs.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $abonnement = $_POST['abonnement'];
    $carte_membre = $_POST['carte_membre'] === 'oui' ? 'oui' : 'non';
    $date_abonnement = $_POST['date_abonnement'];
    $date_expiration_abonnement = $_POST['date_expiration_abonnement'];
    $id_sentier = $_POST['id_sentier'];

    $stmt = $pdo->prepare("UPDATE Visiteur SET abonnement = ?, carte_membre = ?, date_abonnement = ?, date_expiration_abonnement = ?, id_sentier = ? WHERE id_visiteur = ?");
    $stmt->execute([$abonnement, $carte_membre, $date_abonnement, $date_expiration_abonnement, $id_sentier, $id_visiteur]);

    header('Location: gestion_visiteurs.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Visiteur</title>
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
    <h1>Modifier le Visiteur</h1>
    <form action="" method="POST">
        <label for="abonnement">Abonnement :</label>
        <input type="text" id="abonnement" name="abonnement" value="<?= htmlspecialchars($visiteur['abonnement']); ?>" required>

        <label for="carte_membre">Carte Membre :</label>
        <select name="carte_membre" id="carte_membre">
            <option value="oui" <?= $visiteur['carte_membre'] === 'oui' ? 'selected' : ''; ?>>Oui</option>
            <option value="non" <?= $visiteur['carte_membre'] === 'non' ? 'selected' : ''; ?>>Non</option>
        </select>

        <label for="date_abonnement">Date d'Abonnement :</label>
        <input type="date" id="date_abonnement" name="date_abonnement" value="<?= htmlspecialchars($visiteur['date_abonnement']); ?>" required>

        <label for="date_expiration_abonnement">Date d'Expiration :</label>
        <input type="date" id="date_expiration_abonnement" name="date_expiration_abonnement" value="<?= htmlspecialchars($visiteur['date_expiration_abonnement']); ?>" required>

        <label for="id_sentier">ID Sentier :</label>
        <input type="text" id="id_sentier" name="id_sentier" value="<?= htmlspecialchars($visiteur['id_sentier']); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
