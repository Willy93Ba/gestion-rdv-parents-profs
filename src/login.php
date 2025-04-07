<?php
// Récupère le rôle depuis l'URL
$role = isset($_GET['role']) ? $_GET['role'] : null;

// Liste des rôles valides
$roles_valides = ['parent', 'prof', 'admin'];

// Vérifie si le rôle est correct
if (!in_array($role, $roles_valides)) {
    header("Location: index.php"); // Redirection vers l'accueil si mauvais rôle
    exit;
}

// Texte à afficher en fonction du rôle
$titres = [
    'parent' => 'Connexion Parent',
    'prof' => 'Connexion Professeur',
    'admin' => 'Connexion Administrateur'
];

$titre = $titres[$role];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $titre ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            padding: 50px;
        }

        h2 {
            color: #333;
        }

        form {
            display: inline-block;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 250px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        a {
            color: #3498db;
            text-decoration: none;
            display: block;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <h2><?= $titre ?></h2>

    <form action="traitement_login.php" method="post">
        <input type="hidden" name="role" value="<?= $role ?>">
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>

    <a href="index.php">⬅ Retour à l'accueil</a>

</body>
</html>
