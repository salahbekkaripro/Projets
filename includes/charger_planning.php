<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Accès non autorisé !");
}

$user_id = $_SESSION['user_id'];

// Récupérer les données
$stmt = $pdo->prepare("SELECT jour, heure_debut, heure_fin, activite FROM planning WHERE utilisateur_id = ? ORDER BY jour, heure_debut");
$stmt->execute([$user_id]);
$planning = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($planning as $event) {
    echo "<tr>
            <td>" . ucfirst($event['jour']) . "</td>
            <td>" . $event['heure_debut'] . "</td>
            <td>" . $event['heure_fin'] . "</td>
            <td>" . htmlspecialchars($event['activite']) . "</td>
          </tr>";
}
?>
