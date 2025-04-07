<?php
session_start();
require_once("connexion_bdd.php");

// Vérifie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Préparation de la requête
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email AND role = :role");
    $stmt->execute([
        'email' => $email,
        'role' => $role
    ]);

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        // Vérification du mot de passe (ici simple, à sécuriser plus tard)
        if ($mot_de_passe === $utilisateur['mot_de_passe']) {
            $_SESSION['utilisateur'] = $utilisateur;

            // Redirection vers le bon dashboard selon le rôle
            switch ($role) {
                case 'parent':
                    header("Location: menu_parent.php");
                    break;
                case 'prof':
                    header("Location: menu_prof.php");
                    break;
                case 'admin':
                    header("Location: menu_admin.php");
                    break;
                default:
                    header("Location: index.php");
                    break;
            }
            exit;
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Utilisateur non trouvé.";
    }
} else {
    $erreur = "Accès interdit.";
}

// Redirection avec message d'erreur (à améliorer plus tard)
echo "<p style='color:red;text-align:center;'>$erreur</p>";
echo "<p style='text-align:center;'><a href='index.php'>Retour</a></p>";
?>
