<?php
session_start();
require_once("connexion_bdd.php");

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'prof') {
    header("Location: index.php");
    exit;
}

$id_prof = $_SESSION['utilisateur']['id_utilisateur'];

// Traitement des actions : accepter, refuser, supprimer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_rdv'])) {
    $action = $_POST['action'];
    $id_rdv = $_POST['id_rdv'];

    if (in_array($action, ['accepte', 'refuse']) && isset($_POST['motif']) && !empty(trim($_POST['motif']))) {
        $motif = trim($_POST['motif']);
        $pdo->prepare("UPDATE rendezvous SET statut = ?, motif = ? WHERE id_rdv = ?")->execute([$action, $motif, $id_rdv]);

        if ($action === 'refuse') {
            $pdo->prepare("UPDATE creneau SET disponible = 1 WHERE id_creneau = (
                SELECT id_creneau FROM rendezvous WHERE id_rdv = ?
            )")->execute([$id_rdv]);
        }
    }

    if ($action === 'supprimer') {
        $pdo->prepare("UPDATE rendezvous SET supprime_prof = 1 WHERE id_rdv = ?")->execute([$id_rdv]);
    }
}

// Liste des RDV visibles pour le prof
$stmt = $pdo->prepare("
    SELECT r.created_by, r.id_rdv, r.statut, r.motif, c.date_rdv, c.heure_rdv, 
           e.nom AS nom_eleve, e.prenom AS prenom_eleve,
           p.nom AS nom_parent, p.prenom AS prenom_parent
    FROM rendezvous r
    JOIN creneau c ON r.id_creneau = c.id_creneau
    JOIN eleve e ON r.id_eleve = e.id_eleve
    JOIN utilisateur p ON r.id_parent = p.id_utilisateur
    WHERE r.id_prof = ? AND r.supprime_prof = 0
    ORDER BY c.date_rdv, c.heure_rdv
");
$stmt->execute([$id_prof]);
$rdvs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes rendez-vous</title>
    <style>
        body { font-family: Arial; background-color: #f5f7fa; text-align: center; padding: 50px; }
        table { margin: 20px auto; border-collapse: collapse; width: 95%; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background-color: #27ae60; color: white; }
        tr:nth-child(even) { background-color: #ecf0f1; }
        textarea { width: 100%; height: 60px; }
        button { margin-top: 5px; padding: 5px 10px; }
    </style>
</head>
<body>

<h1>📋 Mes rendez-vous</h1>

<?php if (count($rdvs) > 0): ?>
    <table>
        <tr>
            <th>Créé par</th>
            <th>Élève</th>
            <th>Parent</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Statut</th>
            <th>Motif</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($rdvs as $rdv): ?>
            <tr>
                <td><?= strtoupper($rdv['created_by']) ?></td>
                <td><?= htmlspecialchars($rdv['prenom_eleve'] . ' ' . $rdv['nom_eleve']) ?></td>
                <td><?= htmlspecialchars($rdv['prenom_parent'] . ' ' . $rdv['nom_parent']) ?></td>
                <td><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></td>
                <td><?= substr($rdv['heure_rdv'], 0, 5) ?></td>
                <td><strong><?= strtoupper($rdv['statut']) ?></strong></td>
                <td><?= nl2br(htmlspecialchars($rdv['motif'])) ?></td>
                <td>
                    <?php if ($rdv['statut'] === 'en_attente' && $rdv['created_by'] === 'parent'): ?>
                        <form method="post">
                            <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                            <textarea name="motif" placeholder="Motif du refus ou confirmation" required></textarea>
                            <button name="action" value="accepte">✅ Accepter</button>
                            <button name="action" value="refuse">❌ Refuser</button>
                        </form>
                    <?php endif; ?>
                    <form method="post">
                        <input type="hidden" name="id_rdv" value="<?= $rdv['id_rdv'] ?>">
                        <button name="action" value="supprimer" onclick="return confirm('Cacher ce rendez-vous ?')">🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>🙁 Aucun rendez-vous pour l’instant.</p>
<?php endif; ?>

<p><a href="menu_prof.php">⬅ Retour au menu</a></p>

</body>
</html>
