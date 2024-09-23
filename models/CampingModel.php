<?php
class CampingModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCampings() {
        $sql = "SELECT date_reservation, date_debut, date_fin, statut, nombre_personnes, nom_camping, image FROM Camping";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
