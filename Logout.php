<?php
session_start();
session_destroy();
header("Location: index.php"); // Rediriger l'utilisateur vers la page d'accueil après déconnexion
exit();
?>
