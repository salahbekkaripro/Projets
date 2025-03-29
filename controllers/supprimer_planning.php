<?php
session_start();
include '../config/db.php';


// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID du planning manquant.");
}

$id = $_GET['id'];

// Supprimer le planning
$stmt = $pdo->prepare("DELETE FROM planning WHERE id = ?");
$stmt->execute([$id]);

// Redirection après suppression
header("Location: ../views/gestion_planning.php");
exit();
?>
