<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$host = "localhost";
$dbname = "parcNational";
$username = "root";
$password = "root"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reservation_camping = [];

    // Récupére les sentiers
    $sql = "SELECT date_reservation, date_debut, date_fin, statut, nombre_personnes,nom_camping, image FROM reservation_camping";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $reservation_camping = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reservation_camping)) {
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
<section class="section__container destination__container" id="about">
  <h2 class="section__header">Nos camping</h2>
  <p class="section__description">
    Découvre nos camping dans notre parc des calanques
  </p>
  <div class="destination__grid">
    <?php if (!empty($reservation_camping)): ?>
      <?php foreach ($reservation_camping as $reservation_camping): ?>
        <div class="destination__card">

          <img src="<?php echo htmlspecialchars($reservation_camping['image']); ?>" alt="trail" />
          <div class="destination__card__details">
            <div>
              <h4><?php echo htmlspecialchars($reservation_camping['nom_camping']); ?></h4>
              <p>Date de debut: <?php echo htmlspecialchars($reservation_camping['date_debut']); ?></p>
              <p> date de fin:<?php echo htmlspecialchars($reservation_camping['date_fin']); ?></p>
              <p>nombre de personnes: <?php echo htmlspecialchars($reservation_camping['nombre_personnes']); ?></p>
            
            </div>
           
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun camping à afficher pour le moment.</p>
    <?php endif; ?>
  </div>
</section>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Voyage</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<div class="container">
    <h2>Réservation</h2>
    <form id="travelForm">
        <label for="destination">Où allez-vous ?</label>
        <input type="text" id="destination" required>

        <label for="departure">Quand partez-vous ?</label>
        <input type="date" id="departure" required>

        <label for="people">À combien de personnes ?</label>
        <input type="number" id="people" min="1" required>

        <button type="submit">Envoyer</button>
    </form>

    <div class="result" id="result" style="display:none;"></div>
</div>

<script>
    document.getElementById('travelForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const destination = document.getElementById('destination').value;
        const departure = document.getElementById('departure').value;
        const people = document.getElementById('people').value;

        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = `<p>Vous allez à <strong>${destination}</strong>.</p>
                               <p>Vous partez le <strong>${departure}</strong>.</p>
                               <p>Vous serez <strong>${people}</strong> personnes.</p>`;

        // Affichage du résultat puis redirection vers la page de réservation
        resultDiv.style.display = 'block';

        // Redirection après 2 secondes vers la page des choix de réservation
        setTimeout(() => {
            window.location.href = "reservation.html"; // Redirige vers la page de réservation
        }, 100); // 2 secondes d'attente avant la redirection
    });
</script>

</body>
</html>

