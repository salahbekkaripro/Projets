<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">🏠 Accueil</a>
        <a href="../views/gestion_prisonniers.php">👤 Prisonniers</a>
        <a href="../views/surveillance_cellules.php">🏢 Cellules</a>
        <a href="../views/emploi_du_temps.php">📅 Planning</a>
        <a href="../views/gestion_infractions.php">⚖️ Infractions</a>
        <?php
        if (isset($_SESSION['user_id'])) {
            include '../config/db.php';
            $user_id = $_SESSION['user_id'];

            // Récupérer le nombre de notifications non lues
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE utilisateur_id = ? AND lu = 0");
            $stmt->execute([$user_id]);
            $notif_count = $stmt->fetchColumn();
        }
        ?>
        <a href="../views/voir_notifications.php">🔔 Notifications 
            <?php if (!empty($notif_count) && $notif_count > 0): ?>
                <span id="notif-count" class="notif-badge"><?= $notif_count ?></span>
            <?php endif; ?>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../logout.php">🚪 Déconnexion</a>
        <?php else: ?>
            <a href="../index.php">🔑 Connexion</a>
        <?php endif; ?>
    </div>
    <script>
    function updateNotifications() {
        fetch('notif_count.php')
        .then(response => response.text())
        .then(data => {
            let notifBadge = document.getElementById('notif-count');
            if (data > 0) {
                notifBadge.innerText = data;
                notifBadge.style.display = 'inline';
            } else {
                notifBadge.style.display = 'none';
            }
        });
    }

    // Rafraîchir toutes les 10 secondes
    setInterval(updateNotifications, 10000);
    </script>

</body>
</html>
