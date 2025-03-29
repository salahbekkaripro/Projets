<?php
session_start();
include '../config/db.php';
include '../controllers/cellule_controller.php';

// Vérifier si l'utilisateur est Admin (chef)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'chef') {
    header("Location: ../index.php");
    exit();
}

// Récupérer la liste des cellules
$cellules = getAllCellules($pdo);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Cellules</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container">
    <h2>🏢 Gestion des Cellules</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Numéro</th>
                <th>Capacité</th>
                <th>Surveillance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cellules as $cellule): ?>
                <tr>
                    <td><?= $cellule['id'] ?></td>
                    <td><?= htmlspecialchars($cellule['numero_cellule']) ?></td>
                    <td><?= $cellule['capacite'] ?></td>
                    <td><?= $cellule['surveillance'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="../controllers/modifier_cellule.php?id=<?= $cellule['id'] ?>">✏️ Modifier</a>
                        <a href="../controllers/cellule_controller.php?delete=<?= $cellule['id'] ?>" onclick="return confirm('Supprimer cette cellule ?')">❌ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>➕ Ajouter une Cellule</h3>
    <form action="../controllers/cellule_controller.php" method="post">
        <label>Numéro :</label>
        <input type="text" name="numero_cellule" required>
        
        <label>Capacité :</label>
        <input type="number" name="capacite" required>

        <label>Surveillance :</label>
        <select name="surveillance">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>

        <button type="submit" name="ajouter">Ajouter</button>
    </form>
</div>

</body>
</html>
