<?php
session_start();
include '../config/db.php'; // Connexion à la base de données
include '../includes/generation_objet.php'; 


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les notifications de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE utilisateur_id = ? ORDER BY date_notification DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Marquer les notifications comme lues
$pdo->prepare("UPDATE notifications SET lu = 1 WHERE utilisateur_id = ?")->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Notifications</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?> <!-- Barre de navigation -->

<div class="container">
    <h2>Mes Notifications</h2>
    <?php if (empty($notifications)): ?>
        <p>Aucune notification pour le moment.</p>
    <?php else: ?>
        <ul class="notification-list">
            <?php foreach ($notifications as $notif): ?>
                <li class="<?= $notif['lu'] ? 'lue' : 'non-lue' ?>">
                    <strong><?= date('d/m/Y H:i', strtotime($notif['date_notification'])) ?></strong> - <?= htmlspecialchars($notif['message']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
