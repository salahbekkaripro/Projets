<?php
include '../config/db.php';

// Fonction pour récupérer toutes les cellules
function getAllCellules($pdo) {
    $stmt = $pdo->query("SELECT * FROM cellule ORDER BY numero_cellule ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ajouter une cellule
if (isset($_POST['ajouter'])) {
    $numero = $_POST['numero_cellule'];
    $capacite = $_POST['capacite'];
    $surveillance = $_POST['surveillance'];

    $stmt = $pdo->prepare("INSERT INTO cellule (numero_cellule, capacite, surveillance) VALUES (?, ?, ?)");
    $stmt->execute([$numero, $capacite, $surveillance]);

    header("Location: ../views/gestion_cellules.php");
    exit();
}

// Supprimer une cellule
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM cellule WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../views/gestion_cellules.php");
    exit();
}
