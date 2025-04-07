<?php
session_start();
require_once("connexion_bdd.php");

// Vérifie que c'est un parent connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'parent') {
    header("Location: index.php");
    exit;
}

$id_parent = $_SESSION['utilisateur']['id_utilisateur'];

// Récupérer les enfants du parent
$stmt = $pdo->prepare("SELECT * FROM eleve WHERE id_parent = ?");
$stmt->execute([$id_parent]);
$enfants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes enfants</title>
    <style>
        body { font-family: Arial; background-color: #f7f9fc; text-align: center; padding: 50px; }
        h1 { color: #2c3e50; }
        table { margin: 20px auto; border-collapse: collapse; width: 60%; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #ecf0f1; }
    </style>
</head>
<body>

    <h1>👨‍👩‍👧‍👦 Mes enfants</h1>

    <?php if (count($enfants) > 0): ?>
        <table>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
            </tr>
            <?php foreach ($enfants as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>🙁 Aucun enfant trouvé dans votre compte.</p>
    <?php endif; ?>

    <p><a href="menu_parent.php">⬅ Retour au menu</a></p>

</body>
</html>
