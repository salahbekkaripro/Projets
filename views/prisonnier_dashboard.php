<?php
session_start();

// Vérifier si l'utilisateur est bien un prisonnier
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'prisonnier') {
    header('Location: ../index.php'); // Redirection si non prisonnier
    exit();
}

include '../config/db.php';
include '../includes/generation_objet.php'; 



// Récupérer l'ID du prisonnier connecté
$prisonnier_id = $_SESSION['user_id'];

// Récupérer les informations du prisonnier
$stmt = $pdo->prepare("SELECT u.nom, u.prenom, p.cellule_id, p.etat 
                       FROM utilisateur u
                       JOIN prisonnier p ON u.id = p.utilisateur_id
                       WHERE u.id = ?");
$stmt->execute([$prisonnier_id]);
$prisonnier = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer l'emploi du temps du prisonnier
$stmt = $pdo->prepare("SELECT jour, heure_debut, heure_fin, activite FROM planning WHERE utilisateur_id = ?");
$stmt->execute([$prisonnier_id]);
$planning = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les infractions du prisonnier
$stmt = $pdo->prepare("SELECT type_infraction, date_infraction, sanction FROM infraction WHERE prisonnier_id = ?");
$stmt->execute([$prisonnier_id]);
$infractions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Prisonnier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?> <!-- Inclusion de la barre de navigation -->

<div class="dashboard-container">
    <h2>Tableau de Bord - Prisonnier</h2>

    <div class="prisonnier-info">
        <h3>Mes Informations</h3>
        <p><strong>Nom :</strong> <?= $prisonnier['nom'] ?> <?= $prisonnier['prenom'] ?></p>
        <p><strong>Cellule :</strong> <?= $prisonnier['cellule_id'] ?? 'Non assignée' ?></p>
        <p><strong>État :</strong> <?= ucfirst($prisonnier['etat']) ?></p>
    </div>

    <div class="planning">
        <h3>Mon Emploi du Temps</h3>
        <table>
            <tr>
                <th>Jour</th>
                <th>Heure Début</th>
                <th>Heure Fin</th>
                <th>Activité</th>
            </tr>
            <?php foreach ($planning as $event): ?>
                <tr>
                    <td><?= $event['jour'] ?></td>
                    <td><?= $event['heure_debut'] ?></td>
                    <td><?= $event['heure_fin'] ?></td>
                    <td><?= $event['activite'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="infractions">
        <h3>Mes Infractions</h3>
        <table>
            <tr>
                <th>Infraction</th>
                <th>Date</th>
                <th>Sanction</th>
            </tr>
            <?php foreach ($infractions as $inf): ?>
                <tr>
                    <td><?= ucfirst(str_replace('_', ' ', $inf['type_infraction'])) ?></td>
                    <td><?= $inf['date_infraction'] ?></td>
                    <td><?= $inf['sanction'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
