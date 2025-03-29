<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';


$id = $_GET['id'];

// Récupérer les informations du prisonnier
$stmt = $pdo->prepare("SELECT p.*, u.nom, u.prenom, u.email, u.age FROM prisonnier p JOIN utilisateur u ON p.utilisateur_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$prisonnier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prisonnier) {
    die("Prisonnier introuvable.");
}

// Récupérer les cellules disponibles
$cellules = $pdo->query("SELECT id, numero_cellule FROM cellule")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $date_entree = $_POST['date_entree'];
    $date_sortie = $_POST['date_sortie'] ?: NULL;
    $motif_entree = $_POST['motif_entree'];
    $cellule_id = $_POST['cellule_id'] ?: NULL;
    $etat = $_POST['etat'];

    // Mettre à jour les infos utilisateur
    $stmt = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, age = ?, email = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $age, $email, $prisonnier['utilisateur_id']]);

    // Mettre à jour les infos prisonnier
    $stmt = $pdo->prepare("UPDATE prisonnier SET date_entree = ?, date_sortie = ?, motif_entree = ?, cellule_id = ?, etat = ? WHERE id = ?");
    $stmt->execute([$date_entree, $date_sortie, $motif_entree, $cellule_id, $etat, $id]);

    header("Location: ../views/gestion_prisonniers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Prisonnier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Modifier un Prisonnier</h2>
    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= $prisonnier['nom'] ?>" required>
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= $prisonnier['prenom'] ?>" required>
        <label>Âge :</label>
        <input type="number" name="age" min="18" value="<?= $prisonnier['age'] ?>" required>
        <label>Email :</label>
        <input type="email" name="email" value="<?= $prisonnier['email'] ?>" required>
        <label>Date d'Entrée :</label>
        <input type="date" name="date_entree" value="<?= $prisonnier['date_entree'] ?>" required>
        <label>Date de Sortie :</label>
        <input type="date" name="date_sortie" value="<?= $prisonnier['date_sortie'] ?>">
        <label>Motif d'Entrée :</label>
        <input type="text" name="motif_entree" value="<?= $prisonnier['motif_entree'] ?>" required>
        <label>Cellule :</label>
        <select name="cellule_id">
            <option value="">Aucune</option>
            <?php foreach ($cellules as $cellule): ?>
                <option value="<?= $cellule['id'] ?>" <?= ($prisonnier['cellule_id'] == $cellule['id']) ? 'selected' : '' ?>>Cellule <?= $cellule['numero_cellule'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>État :</label>
        <select name="etat">
            <option value="sain" <?= ($prisonnier['etat'] == 'sain') ? 'selected' : '' ?>>Sain</option>
            <option value="malade" <?= ($prisonnier['etat'] == 'malade') ? 'selected' : '' ?>>Malade</option>
            <option value="blessé" <?= ($prisonnier['etat'] == 'blessé') ? 'selected' : '' ?>>Blessé</option>
            <option value="décédé" <?= ($prisonnier['etat'] == 'décédé') ? 'selected' : '' ?>>Décédé</option>
        </select>
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
