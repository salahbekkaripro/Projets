<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';


// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID de cellule manquant.");
}

$id = $_GET['id'];

// Récupérer les informations de la cellule
$stmt = $pdo->prepare("SELECT * FROM cellule WHERE id = ?");
$stmt->execute([$id]);
$cellule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cellule) {
    die("Cellule introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_cellule = $_POST['numero_cellule'];
    $capacite = $_POST['capacite'];
    $surveillance = $_POST['surveillance'];

    $update_stmt = $pdo->prepare("UPDATE cellule SET numero_cellule = ?, capacite = ?, surveillance = ? WHERE id = ?");
    $update_stmt->execute([$numero_cellule, $capacite, $surveillance, $id]);

    header("Location: ../views/cellules.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Cellule</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Modifier la Cellule</h2>
    <form method="post">
        <label>Numéro de Cellule :</label>
        <input type="number" name="numero_cellule" value="<?= $cellule['numero_cellule'] ?>" required>
        <label>Capacité :</label>
        <input type="number" name="capacite" min="1" value="<?= $cellule['capacite'] ?>" required>
        <label>Surveillance :</label>
        <select name="surveillance">
            <option value="1" <?= $cellule['surveillance'] ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= !$cellule['surveillance'] ? 'selected' : '' ?>>Non</option>
        </select>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
