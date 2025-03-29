<?php
session_start();
include '../includes/navbar.php';
include '../config/db.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'chef') {
    header('Location: ../index.php'); // Redirection si non admin
    exit();
}

// Récupérer tous les utilisateurs ayant un planning
$stmt = $pdo->query("SELECT DISTINCT u.id, u.nom, u.prenom 
                      FROM utilisateur u 
                      JOIN planning p ON u.id = p.utilisateur_id");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si un utilisateur a été sélectionné
$plannings = [];
if (isset($_GET['utilisateur_id'])) {
    $utilisateur_id = $_GET['utilisateur_id'];

    // Récupérer les plannings de l'utilisateur sélectionné
    $stmt = $pdo->prepare("SELECT p.id, u.nom, u.prenom, p.jour, p.heure_debut, p.heure_fin, p.activite 
                            FROM planning p 
                            JOIN utilisateur u ON p.utilisateur_id = u.id
                            WHERE u.id = ?");
    $stmt->execute([$utilisateur_id]);
    $plannings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Plannings</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Gestion des Plannings</h2>
        <!-- Bouton pour ajouter un planning -->
        <form method="get" action="../controllers/ajouter_planning.php" style="margin-top: 10px;">
        <button type="submit">➕ Créer un planning</button>
    </form>

    <!-- Sélection de l'utilisateur -->
    <form method="get">
        <label for="utilisateur">Sélectionner un utilisateur :</label>
        <select name="utilisateur_id" id="utilisateur" onchange="this.form.submit()">
            <option value="">-- Choisir un utilisateur --</option>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <option value="<?= $utilisateur['id'] ?>" <?= isset($_GET['utilisateur_id']) && $_GET['utilisateur_id'] == $utilisateur['id'] ? 'selected' : '' ?>>
                    <?= $utilisateur['nom'] . " " . $utilisateur['prenom'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (!empty($plannings)): ?>
        <h3>Planning de <?= htmlspecialchars($plannings[0]['nom'] . " " . $plannings[0]['prenom']) ?></h3>
        <table border="1">
            <tr>
                <th>Jour</th>
                <th>Heure Début</th>
                <th>Heure Fin</th>
                <th>Activité</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($plannings as $planning): ?>
            <tr>
                <td><?= $planning['jour'] ?></td>
                <td><?= $planning['heure_debut'] ?></td>
                <td><?= $planning['heure_fin'] ?></td>
                <td><?= $planning['activite'] ?></td>
                <td>
                    <a href="../controllers/modifier_planning.php?id=<?= $planning['id'] ?>">✏️ Modifier</a>
                    <a href="../controllers/supprimer_planning.php?id=<?= $planning['id'] ?>" onclick="return confirm('Supprimer ce planning ?');">🗑️ Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($_GET['utilisateur_id'])): ?>
        <p>Aucun planning trouvé pour cet utilisateur.</p>
    <?php endif; ?>
</body>
</html>
