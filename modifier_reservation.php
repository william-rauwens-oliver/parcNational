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
    $id_reservation = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM reservation_camping WHERE id_reservation = :id");
    $stmt->execute([':id' => $id_reservation]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_utilisateur = $_POST['id_utilisateur'];
        $id_sentier = $_POST['id_sentier'];
        $date_reservation = $_POST['date_reservation'];

        $stmt = $pdo->prepare("UPDATE reservation_camping SET id_utilisateur = :id_utilisateur, id_sentier = :id_sentier, date_reservation = :date_reservation WHERE id_reservation = :id");
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_sentier' => $id_sentier,
            ':date_reservation' => $date_reservation,
            ':id' => $id_reservation
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
    </style>
</head>
<body>
    <h1>Modifier Réservation</h1>
    <form action="" method="POST">
        <label for="id_utilisateur">ID Utilisateur :</label>
        <input type="number" id="id_utilisateur" name="id_utilisateur" value="<?= htmlspecialchars($reservation['id_utilisateur'] ?? ''); ?>" required>

        <label for="id_sentier">ID Sentier :</label>
        <input type="number" id="id_sentier" name="id_sentier" value="<?= htmlspecialchars($reservation['id_sentier'] ?? ''); ?>" required>

        <label for="date_reservation">Date de Réservation :</label>
        <input type="date" id="date_reservation" name="date_reservation" value="<?= htmlspecialchars($reservation['date_reservation'] ?? ''); ?>" required>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
