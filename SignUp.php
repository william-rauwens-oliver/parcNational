<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include 'Fonctions.php';

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "parcNational";
$conn = connectToDatabase($servername, $username, $password, $dbname);

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $confirm_mot_de_passe = isset($_POST['confirm_mot_de_passe']) ? $_POST['confirm_mot_de_passe'] : '';

    if (!checkPasswordsMatch($mot_de_passe, $confirm_mot_de_passe)) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (isEmailUsed($conn, $email)) {
        $error_message = "L'adresse e-mail est déjà utilisée.";
    } else {
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        if (registerUser($conn, $nom, $prenom, $email, $hashed_password)) {
            header("Location: accueil.php");
            exit();
        } else {
            $error_message = "Erreur : " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/SignUp.css">
   <title>Inscription</title>
</head>
<body>
   <div class="register">
      <img src="assets/Calanque.jpg" alt="register image" class="register__img">

      <form method="POST" class="register__form">
        <h1 class="register__title">Inscription</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
     
        <div class="register__content">
           <div class="register__box">
              <i class="ri-user-3-line register__icon"></i>
              <div class="register__box-input">
                 <input type="text" required name="prenom" class="register__input" id="register-firstname" placeholder=" ">
                 <label for="register-firstname" class="register__label">Prénom</label>
              </div>
           </div>
     
           <div class="register__box">
              <i class="ri-user-3-line register__icon"></i>
              <div class="register__box-input">
                 <input type="text" required name="nom" class="register__input" id="register-lastname" placeholder=" ">
                 <label for="register-lastname" class="register__label">Nom</label>
              </div>
           </div>
     
           <div class="register__box">
              <i class="ri-mail-line register__icon"></i>
              <div class="register__box-input">
                 <input type="email" required name="email" class="register__input" id="register-email" placeholder=" ">
                 <label for="register-email" class="register__label">Email</label>
              </div>
           </div>
     
           <div class="register__box">
              <i class="ri-lock-2-line register__icon"></i>
              <div class="register__box-input">
                 <input type="password" required name="mot_de_passe" class="register__input" id="register-pass" placeholder=" ">
                 <label for="register-pass" class="register__label">Mot de passe</label>
                 <i class="ri-eye-off-line register__eye" id="register-eye"></i>
              </div>
           </div>
     
           <div class="register__box">
              <i class="ri-lock-2-line register__icon"></i>
              <div class="register__box-input">
                 <input type="password" required name="confirm_mot_de_passe" class="register__input" id="register-pass-confirm" placeholder=" ">
                 <label for="register-pass-confirm" class="register__label">Confirmer le mot de passe</label>
                 <i class="ri-eye-off-line register__eye" id="register-eye-confirm"></i>
              </div>
           </div>
        </div>
     
        <button type="submit" class="register__button">Inscription</button>
     
        <p class="register__login">
           Vous avez déjà un compte ? <a href="#">Connectez-vous ici !</a>
        </p>
     </form>     
   </div>

   <script src="assets/js/main.js"></script>
</body>
</html>
