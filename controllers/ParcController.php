<?php
require_once 'models/SentierModel.php';
require_once 'models/CampingModel.php';

class ParcController {
    private $sentierModel;
    private $campingModel;

    public function __construct($pdo) {
        $this->sentierModel = new SentierModel($pdo);
        $this->campingModel = new CampingModel($pdo);
    }

    public function display() {
        $sentiers = $this->sentierModel->getAllSentiers();
        $campings = $this->campingModel->getAllCampings();
        
        require 'views/parcView.php';
    }
}
?>
