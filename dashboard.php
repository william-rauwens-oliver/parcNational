<?php
session_start();

require 'database.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des utilisateurs
$stmt = $pdo->query("SELECT * FROM Utilisateur");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des sentiers
$stmt = $pdo->query("SELECT * FROM Sentier");
$sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des réservations
$stmt = $pdo->query("SELECT * FROM reservation_camping");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des ressources naturelles
$stmt = $pdo->query("SELECT * FROM Ressource_Naturelle");
$ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des campings
$stmt = $pdo->query("SELECT * FROM camping");
$campings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des visiteurs
$stmt = $pdo->query("SELECT * FROM Visiteur");
$visiteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Parc National</title>
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
            <li><a href="#reservations">Réservations</a></li>
            <li><a href="#resources">Ressources Naturelles</a></li>
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
                        <td><?= htmlspecialchars($utilisateur['id_utilisateur'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($utilisateur['nom'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($utilisateur['email'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($utilisateur['role'] ?? 'N/A'); ?></td>
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
                    <th>Image</th>
                    <th>Ville</th>
                    <th>Pays</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sentiers as $sentier) : ?>
                <tr>
                    <td><?= htmlspecialchars($sentier['id_sentier'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($sentier['nom_sentier'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($sentier['description'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($sentier['difficulte'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($sentier['longueur_km'] ?? 'N/A'); ?></td>
                    <td><img src="<?= htmlspecialchars($sentier['image'] ?? 'N/A'); ?>" alt="Image du camping" width="100"></td>
                    <td><?= htmlspecialchars($sentier['ville'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($sentier['pays'] ?? 'N/A'); ?></td>
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


<section id="visiteurs" class="section">
    <h2 class="section__title">Gestion des Visiteurs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Utilisateur</th>
                    <th>Abonnement</th>
                    <th>Carte Membre</th>
                    <th>Date d'Abonnement</th>
                    <th>Date d'Expiration</th>
                    <th>ID Sentier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visiteurs as $visiteur) : ?>
                <tr>
                    <td><?= htmlspecialchars($visiteur['id_visiteur'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($visiteur['id_utilisateur'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($visiteur['abonnement'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($visiteur['carte_membre'] === 'oui' ? 'Oui' : 'Non'); ?></td>
                    <td><?= htmlspecialchars($visiteur['date_abonnement'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($visiteur['date_expiration_abonnement'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($visiteur['id_sentier'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="modifier_visiteur.php?id=<?= $visiteur['id_visiteur']; ?>" class="btn btn--edit">Modifier</a>
                        <a href="supprimer_visiteur.php?id=<?= $visiteur['id_visiteur']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce visiteur ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>


    <section id="add-trail" class="section">
    <h2 class="section__title">Ajouter un Nouveau Sentier</h2>
    <form action="ajouter_sentier.php" method="POST" class="form">
        <label for="nom_sentier">Nom :</label>
        <input type="text" id="nom_sentier" name="nom_sentier" required>
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="difficulte">Difficulté :</label>
        <select id="difficulte" name="difficulte" required>
            <option value="facile">Facile</option>
            <option value="moyenne">Moyenne</option>
            <option value="difficile">Difficile</option>
        </select>
        
        <label for="longueur_km">Longueur (km) :</label>
        <input type="number" id="longueur_km" name="longueur_km" step="0.1" required>
        
        <label for="points_interet">Points d'intérêt :</label>
        <input type="text" id="points_interet" name="points_interet" required>
        
        <label for="image">URL de l'image :</label>
        <input type="text" id="image" name="image" required>

        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required>

        <label for="pays">Pays :</label>
        <input type="text" id="pays" name="pays" required>
        
        <button type="submit" class="btn">Ajouter Sentier</button>
    </form>
</section>


<section id="campsites" class="section">
    <h2 class="section__title">Gestion des Campings</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Localisation</th>
                    <th>Capacité</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campings as $camping) : ?>
                <tr>
                    <td><?= htmlspecialchars($camping['id_camping'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($camping['nom_camping'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($camping['adresse_camping'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($camping['nombre_personnes'] ?? 'N/A'); ?></td>
                    <td><img src="<?= htmlspecialchars($camping['Image'] ?? 'N/A'); ?>" alt="Image du camping" width="100"></td>
                    <td>
                        <a href="modifier_camping.php?id=<?= $camping['id_camping']; ?>" class="btn btn--edit">Modifier</a>
                        <a href="supprimer_camping.php?id=<?= $camping['id_camping']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce camping ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>


<section id="add-camping" class="section">
    <h2 class="section__title">Ajouter un Nouveau Camping</h2>
    <form action="ajouter_camping.php" method="POST" class="form">
        <label for="nom_camping">Nom :</label>
        <input type="text" id="nom_camping" name="nom_camping" required>

        <label for="adresse_camping">Adresse :</label>
        <input type="text" id="adresse_camping" name="adresse_camping" required>

        <label for="nombre_personnes">Capacité (nombre de personnes) :</label>
        <input type="number" id="nombre_personnes" name="nombre_personnes" required>

        <label for="image_camping">URL de l'image :</label>
        <input type="text" id="image_camping" name="image_camping" required>

        <button type="submit" class="btn">Ajouter Camping</button>
    </form>
</section>


<section id="reservations" class="section">
    <h2 class="section__title">Gestion des Réservations</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Utilisateur</th>
                    <th>ID Camping</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Nombre de Personnes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td><?= htmlspecialchars($reservation['id_reservation'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($reservation['id_utilisateur'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($reservation['id_camping'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($reservation['date_debut'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($reservation['date_fin'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($reservation['nombre_personnes'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="modifier_reservation.php?id=<?= $reservation['id_reservation']; ?>" class="btn btn--edit">Modifier</a>
                        <a href="supprimer_reservation.php?id=<?= $reservation['id_reservation']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>


    <section id="resources" class="section">
        <h2 class="section__title">Gestion des Ressources Naturelles</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ressources as $ressource) : ?>
                    <tr>
                        <td><?= htmlspecialchars($ressource['id_ressource'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($ressource['type'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($ressource['nom'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($ressource['description'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($ressource['quantite'] ?? 'N/A'); ?></td>
                        <td>
                            <a href="modifier_ressource.php?id=<?= $ressource['id_ressource']; ?>" class="btn btn--edit">Modifier</a>
                            <a href="supprimer_ressource.php?id=<?= $ressource['id_ressource']; ?>" class="btn btn--delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>


    <section id="add-resource" class="section">
        <h2 class="section__title">Ajouter une Nouvelle Ressource Naturelle</h2>
        <form action="ajouter_ressource.php" method="POST" class="form">
    <label for="type">Type :</label>
    <input type="text" id="type" name="type" required>

    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required></textarea>

    <label for="localisation">Localisation :</label>
    <input type="text" id="localisation" name="localisation" required>

    <label for="date_observation">Date d'observation :</label>
    <input type="date" id="date_observation" name="date_observation" required>

    <label for="etat">État :</label>
    <input type="text" id="etat" name="etat" required>

    <button type="submit" class="btn">Ajouter Ressource</button>
</form>
    </section>
</main>

<script>
    document.getElementById('nav-toggle').addEventListener('click', function() {
        document.getElementById('nav-menu').classList.toggle('nav__menu--active');
    });
</script>

</body>
</html>
