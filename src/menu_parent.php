<?php
session_start();

// Vérifie si l'utilisateur est connecté et si c'est un parent
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'parent') {
    header("Location: index.php");
    exit;
}

$nom = $_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu Parent</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; background-color: #f5f7fa; }
        h1 { color: #333; }
        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .menu a:hover { background-color: #2980b9; }
    </style>
</head>
<body>

    <h1>Bienvenue <?= htmlspecialchars($nom) ?> 👋</h1>
    <p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($nom) ?> </strong>.</p>

    <div class="menu">
        <a href="voir_enfants.php">👨‍👩‍👧‍👦 Mes enfants</a>
        <a href="prendre_rdv.php">📅 Prendre un rendez-vous</a>
        <a href="mes_rdv_parent.php">📋 Mes rendez-vous</a>
        <a href="login.php">🔓 Déconnexion</a>
    </div>

</body>
</html>
