<?php
session_start();
include '../includes/navbar.php';
include '../config/db.php';

// Récupération des prisonniers pour le menu déroulant
$prisonniers = $pdo->query("SELECT p.id, u.nom, u.prenom 
                            FROM prisonnier p 
                            JOIN utilisateur u ON p.utilisateur_id = u.id")
                   ->fetchAll(PDO::FETCH_ASSOC);

// Initialisation
$infractions = [];
$prisonnier_id = $_GET['prisonnier_id'] ?? '';

// Requête avec ou sans filtre
if (!empty($prisonnier_id)) {
    $stmt = $pdo->prepare("SELECT i.id, u.nom, u.prenom, i.type_infraction, i.date_infraction, i.sanction 
                           FROM infraction i 
                           JOIN prisonnier p ON i.prisonnier_id = p.id 
                           JOIN utilisateur u ON p.utilisateur_id = u.id
                           WHERE p.id = ?");
    $stmt->execute([$prisonnier_id]);
} else {
    $stmt = $pdo->query("SELECT i.id, u.nom, u.prenom, i.type_infraction, i.date_infraction, i.sanction 
                         FROM infraction i 
                         JOIN prisonnier p ON i.prisonnier_id = p.id 
                         JOIN utilisateur u ON p.utilisateur_id = u.id");
}
$infractions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Infractions</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Liste des Infractions</h2>
    <a href="../controllers/ajouter_infraction.php">➕ Ajouter une Infraction</a>

    <form method="GET" action="" style="margin: 20px 0;">
        <label for="prisonnier_id">Filtrer par prisonnier :</label>
        <select name="prisonnier_id" onchange="this.form.submit()">
            <option value="">-- Tous les prisonniers --</option>
            <?php foreach ($prisonniers as $p): ?>
                <option value="<?= $p['id'] ?>" <?= ($p['id'] == $prisonnier_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Type d'Infraction</th>
            <th>Date</th>
            <th>Sanction</th>
            <th>Actions</th>
        </tr>
        <?php if (count($infractions) > 0): ?>
            <?php foreach ($infractions as $infraction): ?>
            <tr>
                <td><?= $infraction['id'] ?></td>
                <td><?= $infraction['nom'] ?></td>
                <td><?= $infraction['prenom'] ?></td>
                <td><?= $infraction['type_infraction'] ?></td>
                <td><?= $infraction['date_infraction'] ?></td>
                <td><?= $infraction['sanction'] ? $infraction['sanction'] : 'Aucune' ?></td>
                <td>
                    <a href="../controllers/modifier_infraction.php?id=<?= $infraction['id'] ?>">✏️ Modifier</a>
                    <a href="../controllers/supprimer_infraction.php?id=<?= $infraction['id'] ?>" onclick="return confirm('Supprimer cette infraction ?');">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Aucune infraction trouvée.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
