<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_cellule = $_POST['numero_cellule'];
    $capacite = $_POST['capacite'];
    $surveillance = $_POST['surveillance'];

    // Vérifier si le numéro de cellule existe déjà
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM cellule WHERE numero_cellule = ?");
    $check_stmt->execute([$numero_cellule]);
    $cellule_exists = $check_stmt->fetchColumn();

    if ($cellule_exists) {
        echo "<p style='color: red;'>Erreur : Cette cellule existe déjà.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO cellule (numero_cellule, capacite, surveillance) VALUES (?, ?, ?)");
        $stmt->execute([$numero_cellule, $capacite, $surveillance]);

        header("Location: ../views/cellules.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Cellule</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter une Cellule</h2>
    <form method="post">
        <label>Numéro de Cellule :</label>
        <input type="number" name="numero_cellule" required>
        <label>Capacité :</label>
        <input type="number" name="capacite" min="1" required>
        <label>Surveillance :</label>
        <select name="surveillance">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
