<?php
// Paramètres de connexion
$host = 'localhost';
$dbname = 'rdv_db';
$user = 'root';
$pass = '';

// Connexion à MySQL avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Active le mode erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message (évite de l'afficher en prod)
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
