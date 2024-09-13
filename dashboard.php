<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'parcNational';
$username = 'root';  // Adapter si nécessaire
$password = 'root';  // Adapter si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Gestion des utilisateurs
$stmt = $pdo->query("SELECT * FROM Utilisateur");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion des sentiers
$stmt = $pdo->query("SELECT * FROM Sentier");
$sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs et Sentiers</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/remixicon/fonts/remixicon.css">
</head>
<body>

<nav>
    <div class="nav__container">
        <a href="#" class="nav__logo">ParcNational</a>
        <ul class="nav__menu" id="nav-menu">
            <li><a href="#home">Accueil</a></li>
            <li><a href="#trails">Nos Sentiers</a></li>
            <li><a href="#campsites">Nos Campings</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#">Réserver</a></li>
        </ul>
        <div class="nav__icons">
            <a href="#" class="nav__icon" id="notification-icon">
                <i class="ri-notification-2-line"></i>
                <span class="notification-badge">3</span>
            </a>
            <a href="login.php" class="nav__icon">
                <i class="ri-user-line"></i>
            </a>
            <button class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </button>
        </div>
    </div>
</nav>

<header id="home">
    <div class="header__content">
        <h1>Tableau de Bord</h1>
    </div>
</header>

<main>
    <section id="users" class="section">
        <h2 class="section__title">Gestion des Utilisateurs</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilisateurs as $utilisateur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($utilisateur['nom']); ?></td>
                        <td><?= htmlspecialchars($utilisateur['email']); ?></td>
                        <td><?= htmlspecialchars($utilisateur['role']); ?></td>
                        <td>
                            <a href="modifier_utilisateur.php?id=<?= $utilisateur['id_utilisateur']; ?>" class="btn btn--edit">Modifier</a>
                            <a href="supprimer_utilisateur.php?id=<?= $utilisateur['id_utilisateur']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="trails" class="section">
        <h2 class="section__title">Gestion des Sentiers</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Difficulté</th>
                        <th>Longueur (km)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sentiers as $sentier) : ?>
                    <tr>
                        <td><?= htmlspecialchars($sentier['id_sentier']); ?></td>
                        <td><?= htmlspecialchars($sentier['nom_sentier']); ?></td>
                        <td><?= htmlspecialchars($sentier['description']); ?></td>
                        <td><?= htmlspecialchars($sentier['difficulte']); ?></td>
                        <td><?= htmlspecialchars($sentier['longueur_km']); ?></td>
                        <td>
                            <a href="modifier_sentier.php?id=<?= $sentier['id_sentier']; ?>" class="btn btn--edit">Modifier</a>
                            <a href="supprimer_sentier.php?id=<?= $sentier['id_sentier']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sentier ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script>
    document.getElementById('nav-toggle').addEventListener('click', function() {
        document.getElementById('nav-menu').classList.toggle('nav__menu--active');
    });
</script>

</body>
</html>
