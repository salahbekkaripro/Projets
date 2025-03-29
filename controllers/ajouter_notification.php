<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

// Vérifier si l'utilisateur est bien connecté et a les droits
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'chef' && $_SESSION['role'] !== 'gardien')) {
    die("Accès non autorisé !");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utilisateur_id = $_POST['utilisateur_id'];
    $message = trim($_POST['message']);

    if (!empty($utilisateur_id) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO notifications (utilisateur_id, message) VALUES (?, ?)");
        $stmt->execute([$utilisateur_id, $message]);

        header("Location: ../views/voir_notifications.php?success=1");
        exit();
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Notification</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include 'navbar.php'; ?> <!-- Barre de navigation -->

<div class="container">
    <h2>Envoyer une Notification</h2>

    <form method="POST">
        <label>Utilisateur :</label>
        <select name="utilisateur_id" required>
            <option value="">Sélectionner un utilisateur</option>
            <?php
            // Récupérer tous les utilisateurs
            $users = $pdo->query("SELECT id, nom, prenom FROM utilisateur")->fetchAll();
            foreach ($users as $user) {
                echo "<option value='{$user['id']}'>{$user['nom']} {$user['prenom']}</option>";
            }
            ?>
        </select>

        <label>Message :</label>
        <textarea name="message" required></textarea>

        <button type="submit">Envoyer</button>
    </form>
</div>

</body>
</html>
