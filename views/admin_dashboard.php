<?php
session_start();

include '../config/db.php';
include '../includes/navbar.php';



// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'chef') {
    header('Location: index.php'); // Redirection si non admin
    exit();
}

// Récupérer les statistiques
$total_prisonniers = $pdo->query("SELECT COUNT(*) FROM prisonnier")->fetchColumn();
$total_gardiens = $pdo->query("SELECT COUNT(*) FROM utilisateur WHERE role = 'gardien'")->fetchColumn();
$total_cellules = $pdo->query("SELECT COUNT(*) FROM cellule")->fetchColumn();
$total_infractions = $pdo->query("SELECT COUNT(*) FROM infraction")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Bibliothèque pour les graphiques -->
</head>
<body>

<?php include 'includes/navbar.php'; ?> <!-- Inclusion de la barre de navigation -->

<div class="dashboard-container">
    <h2>Tableau de Bord - Admin</h2>

    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Prisonniers</h3>
            <p><?= $total_prisonniers ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Gardiens</h3>
            <p><?= $total_gardiens ?></p>
        </div>
        <div class="stat-box">
            <h3>Cellules Disponibles</h3>
            <p><?= $total_cellules ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Infractions</h3>
            <p><?= $total_infractions ?></p>
        </div>
    </div>

    <canvas id="chartPrisonniers" width="400" height="200"></canvas> <!-- Graphique -->

    <script>
        var ctx = document.getElementById('chartPrisonniers').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Prisonniers', 'Gardiens', 'Cellules', 'Infractions'],
                datasets: [{
                    label: 'Statistiques Générales',
                    data: [<?= $total_prisonniers ?>, <?= $total_gardiens ?>, <?= $total_cellules ?>, <?= $total_infractions ?>],
                    backgroundColor: ['blue', 'green', 'orange', 'red'],
                }]
            }
        });
    </script>

    <div class="admin-actions">
        <a href="gestion_prisonniers.php" class="btn">Gérer les Prisonniers</a>
        <a href="gestion_cellules.php" class="btn">Gérer les Cellules</a>
        <a href="gestion_infractions.php" class="btn">Voir les Infractions</a>
        <a href="gestion_planning.php" class="btn">Gérer les Plannings</a>
    </div>
</div>

</body>
</html>
