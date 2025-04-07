<?php
session_start();

// Vérifie si l'utilisateur est connecté et si c'est un parent
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'prof') {
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
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            text-align: center;
            padding: 60px;
        }

        h1 {
            color: #2c3e50;
        }

        .menu {
            margin-top: 40px;
        }

        .menu a {
            display: inline-block;
            margin: 15px;
            padding: 15px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
        }

        .menu a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h1>Bienvenue <?= htmlspecialchars($nom) ?> 👋</h1>
    <p>Vous êtes connecté en tant que <strong><?= htmlspecialchars($nom) ?></strong>.</p>

    <div class="menu">
        <a href="prendre_rdv_prof.php">📅 Prendre un rendez-vous</a>
        <a href="mes_rdv_prof.php">🗓️ Voir mes rendez-vous</a>
        <a href="login.php">🔓 Se déconnecter</a>
    </div>

</body>
</html>