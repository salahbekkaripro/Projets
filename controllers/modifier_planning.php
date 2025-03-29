<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID du planning manquant.");
}

$id = $_GET['id'];

// Récupérer les informations du planning
$stmt = $pdo->prepare("SELECT * FROM planning WHERE id = ?");
$stmt->execute([$id]);
$planning = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$planning) {
    die("Planning introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jour = $_POST['jour'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $activite = $_POST['activite'];

    $stmt = $pdo->prepare("UPDATE planning SET jour = ?, heure_debut = ?, heure_fin = ?, activite = ? WHERE id = ?");
    $stmt->execute([$jour, $heure_debut, $heure_fin, $activite, $id]);

    header("Location: ../views/gestion_planning.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Planning</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Modifier un Planning</h2>
    <form method="post">
        <label>Jour :</label>
        <select name="jour" required>
            <option value="Lundi" <?= ($planning['jour'] == 'Lundi') ? 'selected' : '' ?>>Lundi</option>
            <option value="Mardi" <?= ($planning['jour'] == 'Mardi') ? 'selected' : '' ?>>Mardi</option>
            <option value="Mercredi" <?= ($planning['jour'] == 'Mercredi') ? 'selected' : '' ?>>Mercredi</option>
            <option value="Jeudi" <?= ($planning['jour'] == 'Jeudi') ? 'selected' : '' ?>>Jeudi</option>
            <option value="Vendredi" <?= ($planning['jour'] == 'Vendredi') ? 'selected' : '' ?>>Vendredi</option>
            <option value="Samedi" <?= ($planning['jour'] == 'Samedi') ? 'selected' : '' ?>>Samedi</option>
            <option value="Dimanche" <?= ($planning['jour'] == 'Dimanche') ? 'selected' : '' ?>>Dimanche</option>
        </select>
        
        <label>Heure Début :</label>
        <select name="heure_debut" required>
            <?php
            $heures = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
                    '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00'];
            foreach ($heures as $heure) {
                echo "<option value='$heure'>$heure</option>";
            }
            ?>
        </select>

        <label>Heure Fin :</label>
        <select name="heure_fin" required>
            <?php
            foreach ($heures as $heure) {
                echo "<option value='$heure'>$heure</option>";
            }
            ?>
        </select>

        <label>Activité :</label>
        <select name="activite" required>
            <option value="Cellule">Cellule</option>
            <option value="Douche">Douche</option>
            <option value="Cantine">Cantine</option>
            <option value="Promenade">Promenade</option>
        </select>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
