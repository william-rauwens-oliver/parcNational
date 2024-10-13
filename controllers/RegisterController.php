<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once __DIR__ . '/../models/UserModels.php';

function handleRegistration() {
    session_start();  // Démarrage de la session
    $conn = connectToDatabase();
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'] ?? '';

        if (!checkPasswordsMatch($mot_de_passe, $confirm_mot_de_passe)) {
            $error_message = "Les mots de passe ne correspondent pas.";
        }
        elseif (isEmailUsed($conn, $email)) {
            $error_message = "L'adresse e-mail est déjà utilisée.";
        }
        else {
            $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $user_id = registerUser($conn, $nom, $prenom, $email, $hashed_password);

            if ($user_id) {
                $_SESSION['user_id'] = $user_id;  // Ajout de l'ID utilisateur dans la session
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;
                $_SESSION['email'] = $email;
            
                header("Location: Accueil.php");
                exit();
            } else {
                $error_message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }            
        }
        $conn->close();
    }

    include __DIR__ . '/../views/RegisterView.php';
}

handleRegistration();
?>
