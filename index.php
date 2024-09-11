<?php
$host = "localhost";
$dbname = "parcNational";
$username = "root";
$password = "root"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sentiers = [];

    // Récupére les sentiers
    $sql = "SELECT nom_sentier, description, difficulte, longueur_km, points_interet, image, ville, pays FROM Sentier";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sentiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($sentiers)) {
        echo "Aucun sentier trouvé.";
    }

} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
  />
  <link rel="stylesheet" href="styles.css" />
  <title>ParcNational</title>
</head>
<body>
  <nav>
    <div class="nav__header">
      <div class="nav__logo">
        <a href="#" class="logo">ParcNational</a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <i class="ri-menu-line"></i>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li><a href="#home">ACCUEIL</a></li>
      <li><a href="#about">NOS CALANQUES</a></li>
      <li><a href="#tour">NOS CAMPINGS</a></li>
      <li><a href="#contact">CONTACT</a></li>
      <li><a href="#">BOOK TRIP</a></li>
    </ul>
    <div class="nav__btns">
      <button class="btn">VOYAGER</button>
      <div class="nav__icons">
        <!-- icône de notifications -->
        <a href="#" class="nav__icon" id="notification-icon">
          <i class="ri-notification-2-line"></i>
          <span class="notification-badge">3</span>
        </a>
        <!-- icône de connexion -->
        <a href="login.php" class="nav__icon">
          <i class="ri-user-line"></i>
        </a>
      </div>
    </div>
  </nav>

  <!-- Fenêtre modale de notifications -->
  <div class="notification-modal" id="notification-modal">
    <div class="notification-modal__header">
      <h2>Notifications</h2>
      <button class="close-btn" id="close-btn">&times;</button>
    </div>
    <div class="notification-modal__content" id="notification-content">
      <!-- Les notifications seront chargées ici -->
    </div>
  </div>

  <header id="home">
  <div class="header__container">
    <div class="header__content">
      <p>Vous voici dans votre magnifique Parc.</p>
      <h1>Votre Expérience de notre Parc des calanques</h1>
      <div class="header__btns">
        <button class="btn">Réservez dès maintenant !</button>
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

<!-- Modale -->
<div id="videoModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <iframe id="videoFrame" width="560" height="315" src="" frameborder="0" allowfullscreen></iframe>
  </div>
</div>


    <section class="section__container destination__container" id="about">
  <h2 class="section__header">Nos sentiers</h2>
  <p class="section__description">
    Découvre nos sentiers dans notre parc des calanques
  </p>
  <div class="destination__grid">
    <?php if (!empty($sentiers)): ?>
      <?php foreach ($sentiers as $sentier): ?>
        <div class="destination__card">
          <!-- Affichage de l'image du sentier -->
          <img src="<?php echo htmlspecialchars($sentier['image']); ?>" alt="trail" />
          <div class="destination__card__details">
            <div>
              <h4><?php echo htmlspecialchars($sentier['nom_sentier']); ?></h4>
              <p><?php echo htmlspecialchars($sentier['description']); ?></p>
              <p>Difficulté : <?php echo htmlspecialchars($sentier['difficulte']); ?></p>
              <p>Longueur : <?php echo htmlspecialchars($sentier['longueur_km']); ?> km</p>
              <p>Points d'intérêt : <?php echo htmlspecialchars($sentier['points_interet']); ?></p>
              <p>Ville : <?php echo htmlspecialchars($sentier['ville']); ?></p>
              <p>Pays : <?php echo htmlspecialchars($sentier['pays']); ?></p>
            </div>
            <div class="destination__rating">
              <span><i class="ri-star-fill"></i></span>
              4.7
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun sentier à afficher pour le moment.</p>
    <?php endif; ?>
  </div>
</section>
    

    <section class="section__container journey__container" id="tour">
      <h2 class="section__header">Journey To The Sky Made Simple!</h2>
      <p class="section__description">
        Effortless Planning for Your Next Adventure
      </p>
      <div class="journey__grid">
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-bookmark-3-line"></i></span>
            <h4>Seamless Booking Process</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-bookmark-3-line"></i></span>
            <h4>Easy Reservations, One Click Away</h4>
            <p>
              From flights and accommodations to activities and transfers,
              everything you need is available at your fingertips, making travel
              planning effortless.
            </p>
          </div>
        </div>
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-landscape-fill"></i></span>
            <h4>Tailored Itineraries</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-landscape-fill"></i></span>
            <h4>Customized Plans Just for You</h4>
            <p>
              Enjoy personalized travel plans designed to match your preferences
              and interests. Whether you seek adventure or cultural immersion,
              our tailored itineraries ensure your journey is uniquely yours.
            </p>
          </div>
        </div>
        <div class="journey__card">
          <div class="journey__card__bg">
            <span><i class="ri-map-2-line"></i></span>
            <h4>Expert Local Insights</h4>
          </div>
          <div class="journey__card__content">
            <span><i class="ri-map-2-line"></i></span>
            <h4>Insider Tips and Recommendations</h4>
            <p>
              We provide curated recommendations for dining, sightseeing, and
              hidden gems, so you can experience each destination like a local.
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
        <h4>UNLEASH WANDERLUST WITH SKYWINGS</h4>
        <p>
          Embark on a journey like no other with Skywings, where your travel
          dreams come to life. Our mission is to inspire and facilitate your
          adventures, whether you seek the vibrant energy of bustling
          cityscapes, the serene beauty of pristine beaches, or the captivating
          history of ancient landmarks. At Skywings, we provide expertly curated
          destinations and personalized itineraries, ensuring that every trip is
          tailored to your unique preferences. Discover hidden gems, immerse
          yourself in diverse cultures, and create unforgettable memories that
          will last a lifetime.
        </p>
        <p>
          With Skywings as your ultimate travel companion, exploring the wonders
          of the world has never been easier. Our insider tips and local
          insights give you the tools to navigate new places with confidence and
          excitement. From the moment you start planning to the day you return
          home, we are dedicated to making your travel experience seamless and
          enriching.
        </p>
        <div class="showcase__btn">
          <button class="btn">
            Book A Flight Now
            <span><i class="ri-arrow-right-line"></i></span>
          </button>
        </div>
      </div>
    </section>

    <section class="section__container banner__container">
      <div class="banner__card">
        <h4>10+</h4>
        <p>Years Experience</p>
      </div>
      <div class="banner__card">
        <h4>12K</h4>
        <p>Happy Clients</p>
      </div>
      <div class="banner__card">
        <h4>4.8</h4>
        <p>Overall Ratings</p>
      </div>
    </section>

    <section class="section__container discover__container">
      <h2 class="section__header">Discover The World From Above</h2>
      <p class="section__description">
        Experience Breathtaking Views and Unique Perspectives
      </p>
      <div class="discover__grid">
        <div class="discover__card">
          <span><i class="ri-camera-lens-line"></i></span>
          <h4>Aerial Cityscapes</h4>
          <p>
            Witness the architectural marvels and bustling streets from
            bird's-eye view, offering a unique perspective.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-ship-line"></i></span>
          <h4>Coastal Wonders</h4>
          <p>
            Fly over pristine coastlines and turquoise waters, revealing hidden
            coves and vibrant coral reefs.
          </p>
        </div>
        <div class="discover__card">
          <span><i class="ri-landscape-line"></i></span>
          <h4>Historic Landmarks</h4>
          <p>
            Observe the grandeur of ancient castles and other significant sites
            in a way that ground tours can't offer.
          </p>
        </div>
      </div>
    </section>

    <section class="section__container client__container">
      <h2 class="section__header">Loved By Over Thousand Travelers</h2>
      <p class="section__description">
        Discover the stories of wanderlust and cherished memories through the
        eyes of our valued clients.
      </p>
      <!-- Slider main container -->
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
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
                  Skywings has completely transformed my travel experience. From
                  finding hidden gems in bustling cities to discovering serene
                  retreats off the beaten path, every detail was thoughtfully
                  arranged. I can't recommend Skywings enough for anyone looking
                  to elevate their travel experience!
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-1.jpg" alt="client" />
                <div>
                  <h4>John Adams</h4>
                  <h5>Travel Blogger</h5>
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
                  My recent adventure with Skywings was nothing short of
                  spectacular. The personalized itineraries and recommendations
                  they provided led me to extraordinary locations that I would
                  never have found on my own. I'm already planning my next
                  adventure with them!
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-2.jpg" alt="client" />
                <div>
                  <h4>Emily Thompson</h4>
                  <h5>Adventure Enthusiast</h5>
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
                  Skywings offered a transformative experience for my research
                  into historical landmarks. The unique aerial perspectives and
                  provided a new level of appreciation and insight into the
                  sites I studied. I highly recommend their services to fellow
                  historians and cultural enthusiasts.
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-3.jpg" alt="client" />
                <div>
                  <h4>Sarah Lee</h4>
                  <h5>Cultural Historian</h5>
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
                  Finding a balance between work and travel can be challenging,
                  but Skywings made it effortless. Their efficient planning and
                  excellent recommendations helped me maximize my downtime and
                  enjoy every moment of my trip. I look forward to working with
                  them again on future travels.
                </p>
              </div>
              <div class="client__details">
                <img src="assets/client-4.jpg" alt="client" />
                <div>
                  <h4>David Patel</h4>
                  <h5>Business Executive</h5>
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
            <a href="#" class="logo">parcNational</a>
          </div>
          <p>
          Explorez notre parc avec facilité et enthousiasme grâce à 
          notre plateforme de représentation du parc complète. Votre voyage commence ici, 
          où une planification fluide rencontre des expériences inoubliables.
          </p>
          <ul class="footer__socials">
            <li>
              <a href="#"><i class="ri-facebook-fill"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-instagram-line"></i></a>
            </li>
            <li>
              <a href="#"><i class="ri-youtube-line"></i></a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Liens Rapide</h4>
          <ul class="footer__links">
            <li><a href="#">Accueil</a></li>
            <li><a href="#">Nos calanques</a></li>
            <li><a href="#">Nos campings</a></li>
            <li><a href="#">Contact</a></li>
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
                <span><i class="ri-record-mail-line"></i></span> William Chaima Julian
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
        Copyright © 2024 La Plateforme B2 Web, William / Chaima / Julian. Tout droit réservé.
      </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="main.js"></script>
  </body>
</html>
