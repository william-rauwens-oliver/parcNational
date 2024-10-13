<?php
require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$message = '';

if (isset($_GET['id'])) {
    $id_reservation = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM reservation_camping WHERE id_reservation = :id");
    $stmt->execute([':id' => $id_reservation]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_utilisateur = $_POST['id_utilisateur'];
        $id_sentier = $_POST['id_sentier'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $nombre_personnes = $_POST['nombre_personnes'];

        $stmt = $pdo->prepare("UPDATE reservation_camping SET id_utilisateur = :id_utilisateur, id_sentier = :id_sentier, date_debut = :date_debut, date_fin = :date_fin, nombre_personnes = :nombre_personnes WHERE id_reservation = :id");
        
        try {
            $stmt->execute([
                ':id_utilisateur' => $id_utilisateur,
                ':id_sentier' => $id_sentier,
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin,
                ':nombre_personnes' => $nombre_personnes,
                ':id' => $id_reservation
            ]);

            $message = "Réservation mise à jour avec succès.";
        } catch (Exception $e) {
            $message = "Erreur lors de la mise à jour de la réservation : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réservation</title>
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
        .message {
            color: green;
            text-align: center;
            margin: 10px 0;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Modifier Réservation</h1>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="id_utilisateur">ID Utilisateur :</label>
        <input type="number" id="id_utilisateur" name="id_utilisateur" value="<?= htmlspecialchars($reservation['id_utilisateur'] ?? ''); ?>" required>

        <label for="id_sentier">ID Sentier :</label>
        <input type="number" id="id_sentier" name="id_sentier" value="<?= htmlspecialchars($reservation['id_sentier'] ?? ''); ?>" required>

        <label for="date_debut">Date de Début :</label>
        <input type="date" id="date_debut" name="date_debut" value="<?= htmlspecialchars($reservation['date_debut'] ?? ''); ?>" required>

        <label for="date_fin">Date de Fin :</label>
        <input type="date" id="date_fin" name="date_fin" value="<?= htmlspecialchars($reservation['date_fin'] ?? ''); ?>" required>

        <label for="nombre_personnes">Nombre de Personnes :</label>
        <input type="number" id="nombre_personnes" name="nombre_personnes" value="<?= htmlspecialchars($reservation['nombre_personnes'] ?? ''); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
