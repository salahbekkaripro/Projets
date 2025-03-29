<?php
session_start();
include '../config/db.php';
include '../includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $date_entree = $_POST['date_entree'];
    $motif_entree = $_POST['motif_entree'];
    $cellule_id = $_POST['cellule_id'] ?: NULL;
    $etat = $_POST['etat'];

    // Ajouter le prisonnier en tant qu'utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateur (role, nom, prenom, age, email, mot_de_passe) VALUES ('prisonnier', ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $age, $email, $mot_de_passe]);
    $utilisateur_id = $pdo->lastInsertId();

    // Ajouter les informations spécifiques au prisonnier
    $stmt = $pdo->prepare("INSERT INTO prisonnier (utilisateur_id, date_entree, motif_entree, cellule_id, etat) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $date_entree, $motif_entree, $cellule_id, $etat]);

    header("Location: ../views/gestion_prisonniers.php");
    exit();
}

// Récupérer les cellules disponibles
$cellules = $pdo->query("SELECT id, numero_cellule FROM cellule")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Prisonnier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter un Prisonnier</h2>
    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" required>
        <label>Prénom :</label>
        <input type="text" name="prenom" required>
        <label>Âge :</label>
        <input type="number" name="age" min="18" required>
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="mot_de_passe" required>
        <label>Date d'Entrée :</label>
        <input type="date" name="date_entree" required>
        <label>Motif d'Entrée :</label>
        <input type="text" name="motif_entree" required>
        <label>Cellule :</label>
        <select name="cellule_id">
            <option value="">Aucune</option>
            <?php foreach ($cellules as $cellule): ?>
                <option value="<?= $cellule['id'] ?>">Cellule <?= $cellule['numero_cellule'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>État :</label>
        <select name="etat">
            <option value="sain">Sain</option>
            <option value="malade">Malade</option>
            <option value="blessé">Blessé</option>
            <option value="décédé">Décédé</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
