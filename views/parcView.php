<?php
session_start();

$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {}

require './config/database.php';

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$sql = "SELECT * FROM camping";
$result = $conn->query($sql);
$campings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $campings[] = $row;
    }
}

$message = '';

if (isset($_POST['submit'])) {
  $camping_id = $_POST['camping'];
  $date_debut = $_POST['date_debut'];
  $date_fin = $_POST['date_fin'];
  $nombre_personnes = $_POST['nombre_personnes'];

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

  if ($user_id) {
      $check_sql = "SELECT * FROM reservation_camping WHERE id_camping = '$camping_id' AND 
                    (date_debut < '$date_fin' AND date_fin > '$date_debut')";
      $check_result = $conn->query($check_sql);

      if ($check_result->num_rows > 0) {
          $message = "Une réservation existe déjà pour ce camping aux dates spécifiées.";
      } else {
          $insert_sql = "INSERT INTO reservation_camping (date_debut, date_fin, nombre_personnes, statut, id_utilisateur, id_camping) 
                         VALUES ('$date_debut', '$date_fin', '$nombre_personnes', 'confirmée', '$user_id', '$camping_id')";

          if ($conn->query($insert_sql) === TRUE) {
              $message = "Réservation réussie !";
          } else {
              $message = "Erreur : " . $conn->error;
          }
      }
  } else {
      $message = "Erreur : Utilisateur non connecté.";
  }
}


$reserved_dates = [];
if (isset($camping_id)) {
    $sql = "SELECT date_debut, date_fin FROM reservation_camping WHERE id_camping = '$camping_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $start = new DateTime($row['date_debut']);
            $end = new DateTime($row['date_fin']);
            $period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));
            foreach ($period as $date) {
                $reserved_dates[] = $date->format('Y-m-d');
            }
        }
    }
}
$reserved_dates_json = json_encode($reserved_dates);

// Fermer la connexion
$conn->close();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-Content-Type-Options" content="nosniff">
  <meta name="referrer" content="no-referrer" />
  <meta http-equiv="Permissions-Policy" content="geolocation=(), camera=(), microphone=()">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="./css/styles.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <title>ParcNational</title>

</head>
<body>
  <nav>
    <div class="nav__header">
      <div class="nav__logo">
        <a href="Accueil.php" class="logo">ParcNational</a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <i class="ri-menu-line"></i>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li><a href="Accueil.php">ACCUEIL</a></li>
      <li><a href="#Sentiers">NOS SENTIERS</a></li>
      <li><a href="#Campings">NOS CAMPINGS</a></li>
      <li><a href="Ressources.html">NOS RESSOURCES</a></li>
      <li><a href="#contact">CONTACT</a></li>
      <li><a href="#">BOOK TRIP</a></li>
    </ul>
    <div class="nav__btns">
      <button class="btn" onclick="location.href='#tour';">VOYAGER</button>
      <div class="nav__icons">
        <a href="#" class="nav__icon" id="notification-icon">
          <i class="ri-notification-2-line"></i>
          <span class="notification-badge">3</span>
        </a>

        <?php if ($prenom): ?>
          <!-- Si l'utilisateur est connecté, affichez son prénom -->
          <div class="dropdown">
            <span class="nav__user-name"><?php echo htmlspecialchars($prenom); ?></span>
            <div class="dropdown-content">
              <a href="logout.php" class="nav__logout">Déconnexion</a>
              <a href="compte.php" class="nav__logout">Mon compte</a>
            </div>
          </div>
        <?php else: ?>
          <!-- Si l'utilisateur n'est pas connecté, affichez l'icône de connexion -->
          <a href="SignIn.php" class="nav__icon">
            <i class="ri-user-line"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="notification-modal" id="notification-modal">
    <div class="notification-modal__header">
      <h2>Notifications</h2>
      <button class="close-btn" id="close-btn">&times;</button>
    </div>
    <div class="notification-modal__content" id="notification-content">
    </div>
  </div>

  <header id="home">
  <div class="header__container">
    <div class="header__content">
      <p>Vous voici dans votre magnifique Parc.</p>
      <h1>Votre Expérience de notre Parc des calanques</h1>
      <div class="header__btns">
      <button class="btn" onclick="location.href='#Reservation';">Réservez dès maintenant !</button>
        <a href="#" id="openModal">
          <span><i class="ri-play-circle-fill"></i></span>
        </a>
      </div>
    </div>
    <div class="header__image">
      <img src="assets/header.png" alt="header" />
    </div>
  </div>
</header>

<div id="videoModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <iframe id="videoFrame" width="560" height="315" src="" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>

<!-- Section Sentiers -->
<section class="section__container destination__container" id="Sentiers">
    <h2 class="section__header">Nos sentiers</h2>
    <p class="section__description">Découvre nos sentiers dans notre parc des calanques</p>
    <div class="destination__grid">

    <?php if (!empty($sentiers)): ?>
    <?php foreach ($sentiers as $sentier): ?>
        <div class="destination__card">
            <img src="<?php echo htmlspecialchars($sentier['image'] ?? ''); ?>" alt="Image du sentier" />
            <div class="destination__card__details">
                <div>
                    <h4><?php echo htmlspecialchars($sentier['nom_sentier'] ?? 'Nom inconnu'); ?></h4>
                    <p><?php echo htmlspecialchars($sentier['description'] ?? 'Description indisponible'); ?></p>
                    <p>Difficulté : <?php echo htmlspecialchars($sentier['difficulte'] ?? 'Inconnue'); ?></p>
                    <p>Longueur : <?php echo htmlspecialchars($sentier['longueur_km'] ?? 'Inconnue'); ?> km</p>
                    <p>Points d'intérêt : <?php echo htmlspecialchars($sentier['points_interet'] ?? 'Aucun'); ?></p>
                    <p>Ville : <?php echo htmlspecialchars($sentier['ville'] ?? 'Inconnue'); ?></p>
                    <p>Pays : <?php echo htmlspecialchars($sentier['pays'] ?? 'Inconnu'); ?></p>
                </div>
            </div>

            <!-- Formulaire pour devenir visiteur -->
            <form method="POST" action="devenir_visiteur.php">
                <input type="hidden" name="id_utilisateur" value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? ''); ?>">
                <input type="hidden" name="id_sentier" value="<?php echo htmlspecialchars($sentier['id_sentier'] ?? ''); ?>"> <!-- Assurez-vous que ça contient une valeur -->

                <label for="abonnement">Choisissez votre type d'abonnement :</label>
                <select name="abonnement" required>
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                    <option value="VIP">VIP</option>
                </select>

                <label for="carte_membre">Souhaitez-vous une carte membre ?</label>
                <select name="carte_membre" required>
                    <option value="Oui">Oui</option>
                    <option value="Non">Non</option>
                </select>

                <button type="submit" class="btn">Devenir Visiteur</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun sentier à afficher pour le moment.</p>
<?php endif; ?>

    </div>
</section>




<!-- Section Campings -->
<section class="section__container destination__container" id="Campings">
  <h2 class="section__header">Nos campings</h2>
  <p class="section__description">Découvre nos campings dans notre parc des calanques</p>
  <div class="destination__grid">
    <?php if (!empty($campings)): ?>
      <?php foreach ($campings as $camping): ?>
        <div class="destination__card">
          <?php 
            // Récupération de l'URL de l'image
            $image_path = htmlspecialchars($camping['Image']); 
            $camping_name = htmlspecialchars($camping['nom_camping']); 
          ?>
          <img src="<?php echo $image_path; ?>" alt="<?php echo $camping_name; ?>" />
          <div class="destination__card__details">
            <div>
              <h4><?php echo $camping_name; ?></h4>
              <p>Adresse: <?php echo htmlspecialchars($camping['adresse_camping']); ?></p>
              <p>Nombre de personnes: <?php echo htmlspecialchars($camping['nombre_personnes']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun camping à afficher pour le moment.</p>
    <?php endif; ?>
  </div>
</section>



<?php
require './config/database.php';

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Initialiser une variable pour le message
$message = '';

if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $camping_id = $_POST['camping'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $nombre_personnes = $_POST['nombre_personnes'];

    // Récupérer l'ID de l'utilisateur à partir de la session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id) {
        // Vérifier si une réservation existe déjà pour ce camping aux dates spécifiées
        $check_sql = "SELECT date_debut, date_fin FROM reservation_camping 
                      WHERE id_camping = '$camping_id' 
                      AND (
                        (date_debut <= '$date_fin' AND date_fin >= '$date_debut')
                      )";
        
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            // Si une réservation existe pour ces dates, afficher un message d'erreur
            $message = "Une réservation existe déjà pour ce camping aux dates spécifiées. Veuillez choisir d'autres dates.";
        } else {
            // Insérer la nouvelle réservation dans la base de données
            $insert_sql = "INSERT INTO reservation_camping (date_debut, date_fin, nombre_personnes, statut, id_utilisateur, id_camping) 
                           VALUES ('$date_debut', '$date_fin', '$nombre_personnes', 'confirmée', '$user_id', '$camping_id')";

            if ($conn->query($insert_sql) === TRUE) {
                $message = "Réservation réussie !";
            } else {
                $message = "Erreur : " . $conn->error;
            }
        }
    } else {
        $message = "Erreur : Utilisateur non connecté.";
    }
}

$conn->close();
?>

<!-- Formulaire de Réservation -->
<div class="container" id="Reservation">
    <h2>Réservation</h2>
    <form id="travelForm" method="POST" action="">
        <label for="camping">Choisissez un camping :</label>
        <select id="camping" name="camping" required>
            <option value="" disabled selected>Sélectionnez un camping !</option>
            <?php if (!empty($campings)): ?>
                <?php foreach ($campings as $camping): ?>
                    <option value="<?php echo htmlspecialchars($camping['id_camping']); ?>">
                        <?php echo htmlspecialchars($camping['nom_camping']); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="" disabled>Aucun camping disponible</option>
            <?php endif; ?>
        </select>
        
        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required>

        <label for="nombre_personnes">Nombre de personnes :</label>
        <input type="number" id="nombre_personnes" name="nombre_personnes" required min="1">

        <div class="button-container">
          <button type="submit" name="submit">Réserver</button>
        </div>

    </form>
    <?php if ($message): ?>
        <p class="error-message"><?php echo $message; ?></p>
    <?php endif; ?>
</div>


    <section class="section__container journey__container" id="tour">
      <h2 class="section__header">Tes vacances à portée de mains !</h2>
      <p class="section__description">
        Prend rendez-vous dès maintenant pour tes vacances de rêve !
      </p>
      <div class="journey__grid">
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-bookmark-3-line"></i></span>
            <h4>Processus de réservation fluide</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-bookmark-3-line"></i></span>
            <h4>Réservations faciles, en un seul clic</h4>
            <p>
            Des campings et calanques aux activités extraordinaire tout 
            ce dont vous avez besoin est à portée de main, ce qui facilite 
            votre voyage, planifier dès maintenant !
            </p>
          </div>
        </div>
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-landscape-fill"></i></span>
            <h4>Itinéraires sur mesure</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-landscape-fill"></i></span>
            <h4>Des forfaits personnalisés rien que pour vous</h4>
            <p>
            Profitez de plans de campigns personnalisés conçus pour 
            correspondre à vos préférences et intérêts. Que vous recherchiez 
            l'aventure ou l'immersion culturelle.
            </p>
          </div>
        </div>
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-map-2-line"></i></span>
            <h4>Informations locales d'experts</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-map-2-line"></i></span>
            <h4>Conseils et recommandations d’initiés</h4>
            <p>
            Nous fournissons des recommandations organisées pour les restaurants, 
            les visites touristiques pour que vous puissiez découvrir chaque 
            destination.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="section__container showcase__container" id="package">
      <div class="showcase__image">
        <img src="assets/showcase.jpg" alt="showcase" />
      </div>
      <div class="showcase__content">
        <h4>LIBÉREZ L'ENVIE DE VOYAGER</h4>
        <p>
        Partez pour une aventure inoubliable avec nos parcs, 
        où vos rêves de nature prennent vie. Notre mission est de vous 
        inspirer et de faciliter vos escapades, que vous recherchiez 
        l'énergie vibrante des campings animés, la beauté sereine des calanques 
        préservées, ou la découverte fascinante des paysages enchâssés dans 
        l'histoire. Chez nous, nous proposons des destinations soigneusement 
        sélectionnées et des itinéraires personnalisés, garantissant que chaque 
        séjour est adapté à vos préférences uniques. Découvrez des trésors cachés, 
        immergez-vous dans des paysages variés, et créez des souvenirs inoubliables 
        qui dureront toute une vie.
        </p>
        <p>
        Avec nous, votre compagnon de voyage idéal, explorer les merveilles 
        de la nature n’a jamais été aussi simple. Nos conseils d'experts et 
        nos connaissances locales vous offrent les outils nécessaires pour 
        découvrir de nouveaux horizons avec assurance et enthousiasme. 
        Du moment où vous commencez à planifier jusqu'au jour de votre retour, 
        nous sommes engagés à rendre votre expérience en camping fluide et 
        enrichissante.
        </p>
        <div class="showcase__btn">
          <button class="btn">
            Réserve maintenant sans attendre
            <span><i class="ri-arrow-right-line"></i></span>
          </button>
        </div>
      </div>
    </section>

    <section class="section__container banner__container">
      <div class="banner__card">
        <h4>10+</h4>
        <p>D'années d'expérience</p>
      </div>
      <div class="banner__card">
        <h4>12K</h4>
        <p>Client content en réservant chez nous</p>
      </div>
      <div class="banner__card">
        <h4>4.8</h4>
        <p>Notes globales</p>
      </div>
    </section>

    <section class="section__container discover__container">
      <h2 class="section__header">Découvrez le monde vu d'en haut</h2>
      <p class="section__description">
      Découvrez des vues à couper le souffle et des perspectives uniques
      </p>
      <div class="discover__grid">
        <div class="discover__card">
          <span><i class="ri-camera-lens-line"></i></span>
          <h4>Paysages naturel</h4>
          <p>
          Soyez témoin des merveilles architecturales et des rues animées de
          vue plongeante, offrant une perspective unique.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-ship-line"></i></span>
          <h4>Merveilles côtières</h4>
          <p>
          Survolez des côtes immaculées et des eaux turquoise pour découvrir des trésors cachés.
          criques et récifs coralliens vibrants.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-landscape-line"></i></span>
          <h4>Monuments historiques</h4>
          <p>
          Observez la grandeur des architectures et d'autres sites importants
          d'une manière dont vous n'en avez jamais vue.
          </p>
        </div>
      </div>
    </section>

    <section class="section__container client__container">
      <h2 class="section__header">Apprécié par plus de milliers de voyageurs</h2>
      <p class="section__description">
      Découvrez les histoires d'envie de voyager et de souvenirs précieux à travers le
      yeux de nos précieux clients.
      </p>
      <div class="swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__content">
                <div class="client__rating">
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                </div>
                <p>
                Vous avez complètement transformé mon expérience de voyage. Depuis
                  trouver des trésors cachés dans des villes animées pour découvrir sereinement
                  se retire hors des sentiers battus, chaque détail a été soigneusement pensé
                  arrangé. Je recommande leur expérience de voyage !
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-1.jpg" alt="client" />
                <div>
                  <h4>John Adams</h4>
                  <h5>Blogueur de voyage</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__content">
                <div class="client__rating">
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                </div>
                <p>
                Ma récente aventure avec vous n'était rien de moins
                  spectaculaire. Les itinéraires et recommandations personnalisés
                  ils m'ont fourni des endroits extraordinaires que je voudrais
                  je n'ai jamais trouvé par moi-même. Je prépare déjà mon prochain
                  aventure avec eux!
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-2.jpg" alt="client" />
                <div>
                  <h4>Emily Thompson</h4>
                  <h5>Passionné d'aventure</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__content">
                <div class="client__rating">
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                </div>
                <p>
                Vous avez offert une expérience transformatrice pour mes recherches
                  en monuments historiques.Je recommande fortement leurs services à mes collègues
                  historiens et passionnés de culture. Merci beaucoup pour cette expérience inoubliable.
                  Je reviendrais l'année prochaine.
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-3.jpg" alt="client" />
                <div>
                  <h4>Sarah Lee</h4>
                  <h5>Historienne culturel</h5>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <div class="client__content">
                <div class="client__rating">
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                  <span><i class="ri-star-fill"></i></span>
                </div>
                <p>
                Trouver un équilibre entre travail et voyage peut être difficile,
                  mais vous l'avait fait sans effort. Leur planification efficace et
                  d'excellentes recommandations m'ont aidé à maximiser mes temps d'arrêt et
                  profitez de chaque instant de mon voyage.
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-4.jpg" alt="client" />
                <div>
                  <h4>David Patel</h4>
                  <h5>Dirigeant d'entreprise</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer id="contact">
      <div class="section__container footer__container">
        <div class="footer__col">
          <div class="footer__logo">
            <a href="#" class="logo">ParcNational</a>
          </div>
          <p>
          Explorez notre parc avec facilité et enthousiasme grâce à 
          notre plateforme de représentation du parc complète. Votre voyage commence ici, 
          où une planification fluide rencontre des expériences inoubliables.
          </p>
          <ul class="footer__socials">
            <li>
              <a href="https://www.facebook.com/LaPlateformeIO/?locale=fr_FR"><i class="ri-facebook-fill"></i></a>
            </li>
            <li>
              <a href="https://www.instagram.com/LaPlateformeIO/"><i class="ri-instagram-line"></i></a>
            </li>
            <li>
              <a href="https://www.youtube.com/channel/UChT80RwiTVKVGPti__ZEzUg/videos?app=desktop&view=0&sort=dd&shelf_id=0"><i class="ri-youtube-line"></i></a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Liens Rapide</h4>
          <ul class="footer__links">
            <li><a href="Accueil.php">Accueil</a></li>
            <li><a href="#Sentiers">Nos sentiers</a></li>
            <li><a href="#Campings">Nos campings</a></li>
            <li><a href="Ressources.html">Nos ressources</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Contact nous</h4>
          <ul class="footer__links">
            <li>
              <a href="#">
                <span><i class="ri-phone-fill"></i></span> 04 84 89 43 69
              </a>
            </li>
            <li>
              <a href="#">
                <span><i class="ri-record-mail-line"></i></span> Chaima William Julian
              </a>
            </li>
            <li>
              <a href="#">
                <span><i class="ri-map-pin-2-fill"></i></span> Marseille, France
              </a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>S'abonner</h4>
          <form action="/">
            <input type="text" placeholder="Entrez votre email" />
            <button class="btn">S'abonner</button>
          </form>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2024 La Plateforme B2 Web, Chaima / William / Julian. Tout droit réservé.
      </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/script.js"></script>
  </body>
</html>