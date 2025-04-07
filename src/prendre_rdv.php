<?php
session_start();
require_once("connexion_bdd.php");

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'parent') {
    header("Location: index.php");
    exit;
}

$id_parent = $_SESSION['utilisateur']['id_utilisateur'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_eleve = $_POST['id_eleve'];
    $id_prof = $_POST['id_prof'];
    $date_rdv = $_POST['date_rdv'];
    $heure_rdv = $_POST['heure_rdv'];
    $motif = $_POST['motif'];

    // Rechercher si le créneau existe déjà
    $stmt = $pdo->prepare("SELECT * FROM creneau WHERE date_rdv = ? AND heure_rdv = ?");
    $stmt->execute([$date_rdv, $heure_rdv]);
    $creneau = $stmt->fetch();

    if ($creneau && !$creneau['disponible']) {
        $message = "⚠️ Ce créneau n’est plus disponible.";
    } else {
        // Si le créneau n'existe pas, on le crée
        if (!$creneau) {
            $insert = $pdo->prepare("INSERT INTO creneau (date_rdv, heure_rdv, disponible) VALUES (?, ?, 1)");
            $insert->execute([$date_rdv, $heure_rdv]);
            $creneau_id = $pdo->lastInsertId();
        } else {
            $creneau_id = $creneau['id_creneau'];
        }

        // Marquer le créneau comme pris
        $pdo->prepare("UPDATE creneau SET disponible = 0 WHERE id_creneau = ?")->execute([$creneau_id]);

        $insertRDV = $pdo->prepare("INSERT INTO rendezvous (id_parent, id_prof, id_eleve, id_creneau, created_by, statut, motif) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertRDV->execute([$id_parent, $id_prof, $id_eleve, $creneau_id, 'parent', 'en_attente', $motif]);

        $message = "✅ Rendez-vous pris avec succès !";
    }
}

// Récupération des enfants et des profs
$enfants = $pdo->prepare("SELECT * FROM eleve WHERE id_parent = ?");
$enfants->execute([$id_parent]);
$liste_enfants = $enfants->fetchAll();

$profs = $pdo->query("SELECT * FROM utilisateur WHERE role = 'prof'");
$liste_profs = $profs->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre un rendez-vous</title>
    <style>
        body { font-family: Arial; background-color: #f0f4f8; text-align: center; padding: 50px; }
        form { display: inline-block; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; text-align: left; }
        label, select, input, button { display: block; width: 100%; margin-bottom: 15px; padding: 10px; font-size: 16px; }
        p.message { font-weight: bold; color: green; }
    </style>
</head>
<body>

    <h1>📅 Prise de rendez-vous</h1>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>👧 Enfant :</label>
        <select name="id_eleve" required>
            <?php foreach ($liste_enfants as $e): ?>
                <option value="<?= $e['id_eleve'] ?>"><?= htmlspecialchars($e['prenom'] . ' ' . $e['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>👩‍🏫 Professeur :</label>
        <select name="id_prof" required>
            <?php foreach ($liste_profs as $p): ?>
                <option value="<?= $p['id_utilisateur'] ?>"><?= htmlspecialchars($p['prenom'] . ' ' . $p['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>📅 Date :</label>
        <input type="date" name="date_rdv" required min="<?= date('Y-m-d') ?>">

        <label>🕒 Heure :</label>
        <select name="heure_rdv" required>
            <?php
            $heure = strtotime("17:00");
            $fin = strtotime("19:00");
            while ($heure < $fin):
                $val = date("H:i:s", $heure);
                $label = date("H:i", $heure);
            ?>
                <option value="<?= $val ?>"><?= $label ?></option>
            <?php $heure = strtotime("+15 minutes", $heure); endwhile; ?>
        </select>
        <label>📝 Motif du rendez-vous :</label>
        <textarea name="motif" required></textarea>
        <button type="submit">Valider le rendez-vous</button>
    </form>

    <p><a href="menu_parent.php">⬅ Retour au menu</a></p>

</body>
</html>
