<?php
session_start();
include '../config/db.php';

include '../includes/navbar.php';


// Récupérer tous les prisonniers
$stmt = $pdo->query("SELECT p.id, u.nom, u.prenom, p.date_entree, p.date_sortie, c.numero_cellule, p.etat 
                      FROM prisonnier p 
                      JOIN utilisateur u ON p.utilisateur_id = u.id 
                      LEFT JOIN cellule c ON p.cellule_id = c.id");
$prisonniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Prisonniers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Liste des Prisonniers</h2>
    <a href="../controllers/ajouter_prisonnier.php">➕ Ajouter un Prisonnier</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date d'Entrée</th>
            <th>Date de Sortie</th>
            <th>Cellule</th>
            <th>État</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($prisonniers as $prisonnier): ?>
        <tr>
            <td><?= $prisonnier['id'] ?></td>
            <td><?= $prisonnier['nom'] ?></td>
            <td><?= $prisonnier['prenom'] ?></td>
            <td><?= $prisonnier['date_entree'] ?></td>
            <td><?= $prisonnier['date_sortie'] ? $prisonnier['date_sortie'] : 'N/A' ?></td>
            <td><?= $prisonnier['numero_cellule'] ? $prisonnier['numero_cellule'] : 'Non assigné' ?></td>
            <td><?= $prisonnier['etat'] ?></td>
            <td>
                <a href="../controllers/modifier_prisonnier.php?id=<?= $prisonnier['id'] ?>">✏️ Modifier</a>
                <a href="../controllers/supprimer_prisonnier.php?id=<?= $prisonnier['id'] ?>" onclick="return confirm('Supprimer ce prisonnier ?');">🗑️ Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
