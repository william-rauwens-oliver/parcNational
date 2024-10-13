<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
   <link rel="stylesheet" href="./assets/css/SignIn.css">
   <meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data:;">
   <meta http-equiv="X-Content-Type-Options" content="nosniff">
   <meta name="referrer" content="no-referrer" />
   <meta http-equiv="Permissions-Policy" content="geolocation=(), camera=(), microphone=()">
   <title>Connexion</title>
</head>
<body>
   <div class="login">
      <img src="./assets/Calanque.jpg" alt="login image" class="login__img">

      <form method="POST" action="" class="login__form">
         <h1 class="login__title">Connexion</h1>

         <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
         <?php endif; ?>

         <div class="login__content">
            <div class="login__box">
               <i class="ri-user-3-line login__icon"></i>

               <div class="login__box-input">
                  <input type="email" required name="email" class="login__input" id="login-email" placeholder=" ">
                  <label for="login-email" class="login__label">Email</label>
               </div>
            </div>

            <div class="login__box">
               <i class="ri-lock-2-line login__icon"></i>

               <div class="login__box-input">
                  <input type="password" required name="mot_de_passe" class="login__input" id="login-pass" placeholder=" ">
                  <label for="login-pass" class="login__label">Mot de passe</label>
                  <i class="ri-eye-off-line login__eye" id="login-eye"></i>
               </div>
            </div>
         </div>

         <div class="login__check">
            <div class="login__check-group">
               <input type="checkbox" class="login__check-input" id="login-check">
               <label for="login-check" class="login__check-label">Se rappeler de moi</label>
            </div>

            <a href="#" class="login__forgot">Mot de passe oublié ?</a>
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