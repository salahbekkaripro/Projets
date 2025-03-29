<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID de l'infraction manquant.");
}

$id = $_GET['id'];

// Récupérer les informations de l'infraction
$stmt = $pdo->prepare("SELECT * FROM infraction WHERE id = ?");
$stmt->execute([$id]);
$infraction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$infraction) {
    die("Infraction introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type_infraction = $_POST['type_infraction'];
    $sanction = $_POST['sanction'];

    $stmt = $pdo->prepare("UPDATE infraction SET type_infraction = ?, sanction = ? WHERE id = ?");
    $stmt->execute([$type_infraction, $sanction, $id]);

    header("Location: ../views/gestion_infractions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Infraction</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Modifier une Infraction</h2>
    <form method="post">
        <label>Type d'Infraction :</label>
        <select name="type_infraction" required>
            <option value="tentative_evasion" <?= ($infraction['type_infraction'] == 'tentative_evasion') ? 'selected' : '' ?>>Tentative d'évasion</option>
            <option value="meurtre" <?= ($infraction['type_infraction'] == 'meurtre') ? 'selected' : '' ?>>Meurtre</option>
            <option value="possession_objet_interdit" <?= ($infraction['type_infraction'] == 'possession_objet_interdit') ? 'selected' : '' ?>>Objet interdit</option>
            <option value="mutinerie" <?= ($infraction['type_infraction'] == 'mutinerie') ? 'selected' : '' ?>>Mutinerie</option>
        </select>

        <label>Sanction :</label>
        <input type="text" name="sanction" value="<?= $infraction['sanction'] ?>" required>

        <button type="submit">Modifier</button>
    </form>
</body>
</html>
