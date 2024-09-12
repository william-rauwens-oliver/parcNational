<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'parcNational'; // Ton nom de base de données
$username = 'root'; // Ton identifiant MySQL
$password = 'root'; // Ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier si l'utilisateur existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {
        // L'utilisateur est authentifié, démarrer la session
        $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
        $_SESSION['email'] = $utilisateur['email'];
        $_SESSION['role'] = $utilisateur['role']; // Par exemple, admin ou visiteur

        // Rediriger vers le tableau de bord (par exemple dashboard.php)
        header("Location: dashboard.php");
        exit();
    } else {
        // Si l'authentification échoue
        $message = "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/SignIn.css">
    <title>Connexion</title>
</head>
<body>
    <div class="login">
        <img src="assets/Calanque.jpg" alt="login image" class="login__img">

        <form action="login.php" method="POST" class="login__form">
            <h1 class="login__title">Connexion</h1>

            <?php if (!empty($message)) : ?>
                <p style="color: red;"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <div class="login__content">
                <div class="login__box">
                    <i class="ri-user-3-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="email" name="email" required class="login__input" id="login-email" placeholder=" ">
                        <label for="login-email" class="login__label">Email</label>
                    </div>
                </div>

                <div class="login__box">
                    <i class="ri-lock-2-line login__icon"></i>
                    <div class="login__box-input">
                        <input type="password" name="password" required class="login__input" id="login-pass" placeholder=" ">
                        <label for="login-pass" class="login__label">Mots de passe</label>
                        <i class="ri-eye-off-line login__eye" id="login-eye"></i>
                    </div>
                </div>
            </div>

            <div class="login__check">
                <div class="login__check-group">
                    <input type="checkbox" class="login__check-input" id="login-check">
                    <label for="login-check" class="login__check-label">Se rappeler de moi</label>
                </div>

                <a href="#" class="login__forgot">Mots de passe oublié ?</a>
            </div>

            <button type="submit" class="login__button">Connexion</button>

            <p class="login__register">
                Vous n'avez pas de compte ? <a href="SignUp.php">Créez-en un !</a>
            </p>
        </form>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
