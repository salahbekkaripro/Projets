<?php
session_start();
include '../config/db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si un ID est passé en paramètre
if (!isset($_GET['id'])) {
    die("ID de cellule manquant.");
}

$id = $_GET['id'];

// Supprimer la cellule
$stmt = $pdo->prepare("DELETE FROM cellule WHERE id = ?");
$stmt->execute([$id]);

// Redirection après suppression
header("Location: ../views/cellules.php");
exit();
?>
