<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - RDV Parents-Profs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8fa;
            text-align: center;
            padding: 60px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h1>Bienvenue 👋</h1>
    <p>Application de gestion des rendez-vous entre parents et professeurs.</p>

    <div class="btn-container">
        <a class="btn" href="login.php?role=parent">Connexion Parent</a>
        <a class="btn" href="login.php?role=prof">Connexion Professeur</a>
        <a class="btn" href="login.php?role=admin">Connexion Admin</a>
    </div>

</body>
</html>
