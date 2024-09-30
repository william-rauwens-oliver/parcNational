<?php
class CampingModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCampings() {
        $sql = "SELECT nombre_personnes, nom_camping, adresse_camping, image FROM Camping";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
