<?php
function connectToDatabase() {
    require './config/database.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données: " . $conn->connect_error);
    }
    return $conn;
}

function isEmailUsed($conn, $email) {
    $stmt = $conn->prepare("SELECT email FROM Utilisateur WHERE email = ?");
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function registerUser($conn, $nom, $prenom, $email, $hashed_password) {
    $role = 'utilisateur';
    $stmt = $conn->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role, date_creation) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt === false) {
        die("Erreur de préparation de la requête SQL: " . $conn->error);
    }

    $stmt->bind_param("sssss", $nom, $prenom, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        return true;
    } else {
        echo "Erreur lors de l'exécution de la requête: " . $stmt->error;
        return false;
    }
}

function checkPasswordsMatch($password, $confirmPassword) {
    return $password === $confirmPassword;
}
?>