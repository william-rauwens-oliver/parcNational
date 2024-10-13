<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_utilisateur = $_SESSION['user_id'] ?? null;
    $id_sentier = $_POST['id_sentier'] ?? null; // Récupère l'id_sentier depuis le formulaire
    $abonnement = $_POST['abonnement'] ?? null;
    $carte_membre = $_POST['carte_membre'] ?? null;

    // Vérifier que tous les champs sont remplis
    if ($id_utilisateur === null || $id_sentier === null || $abonnement === null || $carte_membre === null) {
        die("Erreur : Tous les champs doivent être remplis.");
    }

    // Connexion à la base de données
    require_once 'config/database.php';

    $date_abonnement = date('Y-m-d');
    $date_expiration_abonnement = date('Y-m-d', strtotime('+1 year'));

    // Insertion dans la base de données (sans id_sentier dans Visiteur)
    $sql = "INSERT INTO Visiteur (id_utilisateur, abonnement, carte_membre, date_abonnement, date_expiration_abonnement)
            VALUES (:id_utilisateur, :abonnement, :carte_membre, :date_abonnement, :date_expiration_abonnement)";

    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':abonnement' => $abonnement,
            ':carte_membre' => $carte_membre,
            ':date_abonnement' => $date_abonnement,
            ':date_expiration_abonnement' => $date_expiration_abonnement
        ]);
        
        echo "Inscription réussie en tant que visiteur !";

        // Si vous avez besoin d'enregistrer ou d'utiliser l'id_sentier, vous pouvez le faire ici

    } catch (Exception $e) {
        echo 'Erreur lors de l\'ajout du visiteur : ' . $e->getMessage();
    }
}
?>
