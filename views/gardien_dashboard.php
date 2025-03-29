<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';


// Vérifier si l'utilisateur est bien un gardien
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'gardien') {
    header('Location: ../index.php'); // Redirection si non gardien
    exit();
}



// Récupérer les statistiques pour les gardiens
$total_prisonniers = $pdo->query("SELECT COUNT(*) FROM prisonnier")->fetchColumn();
$total_infractions = $pdo->query("SELECT COUNT(*) FROM infraction")->fetchColumn();
$total_cellules = $pdo->query("SELECT COUNT(*) FROM cellule")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gardien</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Graphiques interactifs -->
</head>
<body>

<?php include '../includes/navbar.php'; ?> <!-- Inclusion de la barre de navigation -->

<div class="dashboard-container">
    <h2>Tableau de Bord - Gardien</h2>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Prisonniers</h3>
            <p><?= $total_prisonniers ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Infractions</h3>
            <p><?= $total_infractions ?></p>
        </div>
        <div class="stat-box">
            <h3>Cellules à Surveiller</h3>
            <p><?= $total_cellules ?></p>
        </div>
    </div>

    <canvas id="chartGardiens" width="400" height="200"></canvas> <!-- Graphique -->

    <script>
        var ctx = document.getElementById('chartGardiens').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Prisonniers', 'Infractions', 'Cellules'],
                datasets: [{
                    label: 'Statistiques Gardien',
                    data: [<?= $total_prisonniers ?>, <?= $total_infractions ?>, <?= $total_cellules ?>],
                    backgroundColor: ['blue', 'red', 'orange'],
                }]
            }
        });
    </script>

    <div class="admin-actions">
        <a href="gestion_cellules.php" class="btn">Gérer les Cellules</a>
        <a href="gestion_infractions.php" class="btn">Signaler une Infraction</a>
        <a href="gestion_prisonniers.php" class="btn">Surveiller les Prisonniers</a>
    </div>
</div>

</body>
</html>
