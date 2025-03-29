<?php
// Démarrer la session
session_start();
include 'config/db.php';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Redirection automatique vers le bon dashboard selon le rôle
    switch ($_SESSION['role']) {
        case 'chef':
            header('Location: views/admin_dashboard.php');
            exit();
        case 'gardien':
            header('Location: views/gardien_dashboard.php');
            exit();
        case 'prisonnier':
            header('Location: views/prisonnier_dashboard.php');
            exit();
        case 'cuisinier':
            header('Location: views/cuisinier_dashboard.php');
            exit();
        default:
            header('Location: index.php');
            exit();
    }
}

// Vérification de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifier si l'email existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérification du mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirection selon le rôle
            switch ($user['role']) {
                case 'chef':
                    header('Location: views/admin_dashboard.php');
                    break;
                case 'gardien':
                    header('Location: views/gardien_dashboard.php');
                    break;
                case 'prisonnier':
                    header('Location: views/prisonnier_dashboard.php');
                    break;
                case 'cuisinier':
                    header('Location: views/cuisinier_dashboard.php');
                    break;
                default:
                    header('Location: index.php');
                    break;
            }
            exit();
        } else {
            $error_message = "Identifiants incorrects";
        }
    } else {
        $error_message = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Prison Simulator</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
</body>
</html>
