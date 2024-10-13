<?php
class SentierModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSentiers() {
        $sql = "SELECT nom_sentier, description, difficulte, longueur_km, points_interet, image, ville, pays FROM Sentier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addVisiteur($id_utilisateur, $abonnement, $carte_membre) {
        $date_abonnement = date('Y-m-d'); // Date actuelle
        $date_expiration_abonnement = date('Y-m-d', strtotime('+1 year')); // Expiration après un an

        $sql = "INSERT INTO Visiteur (id_utilisateur, abonnement, carte_membre, date_abonnement, date_expiration_abonnement) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_utilisateur, $abonnement, $carte_membre, $date_abonnement, $date_expiration_abonnement]);
    }
}
?>
