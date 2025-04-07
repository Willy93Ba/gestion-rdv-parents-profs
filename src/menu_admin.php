<?php
session_start();

// Vérifie que l'utilisateur est bien un admin
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$prenom = $_SESSION['utilisateur']['prenom'];
$nom = $_SESSION['utilisateur']['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu Administrateur</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background-color: #f0f0f0; }
        h1 { color: #2c3e50; }
        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #e67e22;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .menu a:hover { background-color: #d35400; }
    </style>
</head>
<body>

<h1>Bienvenue <?= htmlspecialchars($nom) ?> 👋</h1>
<p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($nom) ?></strong>.</p>

    <div class="menu">
        <a href="gestion_creneaux.php">🕒 Gérer les créneaux</a>
        <a href="tous_rdv.php">📋 Tous les rendez-vous</a>
        <a href="gestion_utilisateurs.php">👥 Utilisateurs</a>
        <a href="gestion_eleves.php">👨‍👧 Gérer les élèves</a>
        <a href="login.php">🔓 Déconnexion</a>
    </div>

</body>
</html>