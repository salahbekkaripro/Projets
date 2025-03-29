<?php
session_start();
include '../includes/navbar.php';
include '../config/db.php';

// Récupérer les prisonniers pour le formulaire
$stmt = $pdo->query("SELECT p.id, u.nom, u.prenom 
                    FROM prisonnier p 
                    JOIN utilisateur u ON p.utilisateur_id = u.id");
$prisonniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Infraction</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter une Infraction</h2>
    <form action="traitement_ajout_infraction.php" method="POST">
        <label for="prisonnier_id">Prisonnier :</label>
        <select name="prisonnier_id" required>
            <?php foreach ($prisonniers as $prisonnier): ?>
                <option value="<?= $prisonnier['id'] ?>">
                    <?= htmlspecialchars($prisonnier['nom'] . ' ' . $prisonnier['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="type_infraction">Type d'infraction :</label>
        <select name="type_infraction" required>
            <option value="tentative évasion">Tentative d’évasion</option>
            <option value="meurtre">Meurtre</option>
            <option value="possession objet interdit">Possession d’objet interdit</option>
            <option value="mutinerie">Mutinerie</option>
        </select><br><br>

        <label for="date_infraction">Date de l'infraction :</label>
        <input type="datetime-local" name="date_infraction" required><br><br>

        <label for="sanction">Sanction :</label>
        <input type="text" name="sanction" placeholder="Ex: isolement 3 jours"><br><br>

        <input type="submit" value="Ajouter">
    </form>
</body>
</html>
