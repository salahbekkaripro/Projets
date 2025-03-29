<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utilisateur_id = $_POST['utilisateur_id'];
    $jour = $_POST['jour'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $activite = $_POST['activite'];

    $stmt = $pdo->prepare("INSERT INTO planning (utilisateur_id, jour, heure_debut, heure_fin, activite) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $jour, $heure_debut, $heure_fin, $activite]);

    header("Location: ../views/gestion_planning.php");
    exit();
}

// Récupérer les utilisateurs
$utilisateurs = $pdo->query("SELECT id, nom, prenom FROM utilisateur")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Planning</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter un Planning</h2>
    <form method="post">
        <label>Utilisateur :</label>
        <select name="utilisateur_id" required>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <option value="<?= $utilisateur['id'] ?>">
                    <?= $utilisateur['nom'] ?> <?= $utilisateur['prenom'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Jour :</label>
        <select name="jour" required>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
            <option value="Dimanche">Dimanche</option>
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

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
