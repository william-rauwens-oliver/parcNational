<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once __DIR__ . '/../models/UserModels.php';

function handleLogin() {
    $conn = connectToDatabase();
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';

        $stmt = $conn->prepare("SELECT id_utilisateur, mot_de_passe, nom, prenom FROM Utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password, $nom, $prenom);
        $stmt->fetch();
        $stmt->close();
        
        if ($hashed_password) {
            if (password_verify($mot_de_passe, $hashed_password)) {
                session_start();
                $_SESSION['user_id'] = $user_id;  // Ajout de l'ID utilisateur dans la session
                $_SESSION['email'] = $email;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
        
                header("Location: Accueil.php");
                exit();
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Aucun compte trouvé avec cet email.";
        }        
        $conn->close();
    }

    include __DIR__ . '/../views/SignInView.php';
}

handleLogin();
?>
