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
}
?>
