<?php
function connectToDatabase($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    return $conn;
}

function checkPasswordsMatch($password, $confirmPassword) {
    return $password === $confirmPassword;
}

function isEmailUsed($conn, $email) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Utilisateur WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

function registerUser($conn, $nom, $prenom, $email, $hashedPassword) {
    $stmt = $conn->prepare("INSERT INTO Utilisateur (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, 'utilisateur')");
    $stmt->bind_param("ssss", $nom, $prenom, $email, $hashedPassword);
    return $stmt->execute();
}
?>
