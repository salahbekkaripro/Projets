<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';
include '../includes/generation_objet.php'; 


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Définir les jours et heures pour l'emploi du temps (format correct pour MySQL TIME)
$jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
$horaires = ['08:00:00', '08:30:00', '09:00:00', '09:30:00', 
             '10:00:00', '10:30:00', '11:00:00', '11:30:00',
             '12:00:00', '12:30:00', '13:00:00', '13:30:00',
             '14:00:00', '14:30:00', '15:00:00', '15:30:00',
             '16:00:00', '16:30:00', '17:00:00', '17:30:00',
             '18:00:00', '18:30:00', '19:00:00'];

// Récupérer les activités de l'utilisateur
$stmt = $pdo->prepare("SELECT jour, heure_debut, heure_fin, activite FROM planning WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$planning = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si des données existent
if (empty($planning)) {
    echo "<p>Aucun emploi du temps trouvé.</p>";
}

// Fonction pour afficher une case de cours si elle correspond à une activité
function afficherCours($jour, $heure, $planning, &$cellsFusionnees) {
    foreach ($planning as $event) {
        $debut = strtotime($event['heure_debut']);
        $fin = strtotime($event['heure_fin']);
        $current = strtotime($heure);

        // Vérifier si cette cellule a déjà été fusionnée et ne pas l'afficher à nouveau
        if (isset($cellsFusionnees[$jour][$heure])) {
            return ""; // Évite de générer des cases vides rouges
        }

        // Si c'est le début d'un événement, on applique le rowspan correct
        if ($event['jour'] === $jour && $debut == $current) {
            $rowspan = ($fin - $debut) / 1800; // 30 min = 1 ligne

            // Marquer toutes les cellules comme fusionnées pour éviter les trous
            for ($i = $debut + 1800; $i < $fin; $i += 1800) {
                $heureStr = date('H:i:s', $i);
                $cellsFusionnees[$jour][$heureStr] = true;
            }

            return "<td class='cours' rowspan='$rowspan'>" 
                . "<span class='event-name'>" . htmlspecialchars($event['activite']) . "</span>"
                . "<div class='event-time' style='display:block; background:red; padding:5px;'>" 
                . date('H:i', $debut) . " - " . date('H:i', $fin) . "</div>"
                . "</td>";

        }
    }

    return "<td></td>"; // Case vide si pas d'activité
}




?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du Temps</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>📅 Mon Emploi du Temps</h2>
    <table class="emploi-du-temps">
        <thead>
            <tr>
                <th>Heure</th>
                <?php foreach ($jours as $jour): ?>
                    <th><?= htmlspecialchars($jour) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $cellsFusionnees = []; // Tableau pour éviter les duplications des cours
            foreach ($horaires as $heure): ?>
                <tr>
                    <td class="heure"><?= date('H:i', strtotime($heure)) ?></td>
                    <?php foreach ($jours as $jour): ?>
                        <?php 
                            // Afficher la cellule normalement sinon
                            echo afficherCours($jour, $heure, $planning, $cellsFusionnees);
                        ?>
                    <?php endforeach; ?>


                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>