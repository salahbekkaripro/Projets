<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';
include '../includes/generation_objet.php'; 


// Récupérer toutes les cellules
$stmt = $pdo->query("SELECT * FROM cellule");
$cellules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Cellules</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Liste des Cellules</h2>
    <a href="../controllers/ajouter_cellule.php">➕ Ajouter une cellule</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Numéro</th>
            <th>Capacité</th>
            <th>Surveillance</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($cellules as $cellule): ?>
        <tr>
            <td><?= $cellule['id'] ?></td>
            <td><?= $cellule['numero_cellule'] ?></td>
            <td><?= $cellule['capacite'] ?></td>
            <td><?= $cellule['surveillance'] ? 'Oui' : 'Non' ?></td>
            <td>
                <a href="modifier_cellule.php?id=<?= $cellule['id'] ?>">✏️ Modifier</a>
                <a href="supprimer_cellule.php?id=<?= $cellule['id'] ?>" onclick="return confirm('Supprimer cette cellule ?');">🗑️ Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
