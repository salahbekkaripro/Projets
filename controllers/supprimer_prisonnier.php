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
    die("ID du prisonnier manquant.");
}

$id = $_GET['id'];

// Supprimer le prisonnier de la table prisonnier
$stmt = $pdo->prepare("DELETE FROM prisonnier WHERE id = ?");
$stmt->execute([$id]);

// Supprimer l'utilisateur correspondant
$stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id = ?");
$stmt->execute([$id]);

// Redirection après suppression
header("Location: ../views/gestion_prisonniers.php");
exit();
?>
