<?php
session_start();
require_once("connexion_bdd.php");

// Vérifie que c'est un prof connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'prof') {
    header("Location: index.php");
    exit;
}

$id_prof = $_SESSION['utilisateur']['id_utilisateur'];
$message = '';

// 📥 Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_eleve = $_POST['id_eleve'];
    $date_rdv = $_POST['date_rdv'];
    $heure_rdv = $_POST['heure_rdv'];
    $motif = $_POST['motif'];

    // Récupère le parent de l'élève
    $parent_query = $pdo->prepare("SELECT id_parent FROM eleve WHERE id_eleve = ?");
    $parent_query->execute([$id_eleve]);
    $parent = $parent_query->fetch();

    if ($parent) {
        $id_parent = $parent['id_parent'];

        // Cherche ou crée le créneau
        $stmt = $pdo->prepare("SELECT * FROM creneau WHERE date_rdv = ? AND heure_rdv = ?");
        $stmt->execute([$date_rdv, $heure_rdv]);
        $creneau = $stmt->fetch();

        if ($creneau && !$creneau['disponible']) {
            $message = "⚠️ Ce créneau est déjà pris.";
        } else {
            if (!$creneau) {
                $insert = $pdo->prepare("INSERT INTO creneau (date_rdv, heure_rdv, disponible) VALUES (?, ?, 1)");
                $insert->execute([$date_rdv, $heure_rdv]);
                $creneau_id = $pdo->lastInsertId();
            } else {
                $creneau_id = $creneau['id_creneau'];
            }

            // Marquer indisponible
            $pdo->prepare("UPDATE creneau SET disponible = 0 WHERE id_creneau = ?")->execute([$creneau_id]);

            $insertRDV = $pdo->prepare("INSERT INTO rendezvous (id_parent, id_prof, id_eleve, id_creneau, created_by, statut, motif) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertRDV->execute([$id_parent, $id_prof, $id_eleve, $creneau_id, 'prof', 'en_attente', $motif]);
            $message = "✅ Rendez-vous créé avec succès !";
        }
    } else {
        $message = "❌ Élève introuvable.";
    }
}

// 🔍 Liste des élèves
$eleves = $pdo->query("
    SELECT e.id_eleve, e.nom, e.prenom, u.nom AS nom_parent, u.prenom AS prenom_parent
    FROM eleve e
    JOIN utilisateur u ON e.id_parent = u.id_utilisateur
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Convocation d’un élève</title>
    <style>
        body { font-family: Arial; text-align: center; background-color: #f7f9fc; padding: 50px; }
        form { display: inline-block; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 0 10px #ccc; text-align: left; }
        label, select, input, button { display: block; width: 100%; margin-bottom: 15px; padding: 10px; font-size: 16px; }
        .message { font-weight: bold; color: green; }
    </style>
</head>
<body>

    <h1>📩 Convocation d’un élève</h1>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="post">
        <label>👧 Élève (et parent)</label>
        <select name="id_eleve" required>
            <?php foreach ($eleves as $e): ?>
                <option value="<?= $e['id_eleve'] ?>">
                    <?= htmlspecialchars($e['prenom'] . ' ' . $e['nom']) ?> (parent : <?= htmlspecialchars($e['prenom_parent'] . ' ' . $e['nom_parent']) ?>)
                </option>
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
        <button type="submit">Envoyer la convocation</button>
    </form>

    <p><a href="menu_prof.php">⬅ Retour au menu</a></p>

</body>
</html>
