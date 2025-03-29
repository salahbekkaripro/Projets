<?php
session_start();
include '../config/db.php';
include '../config/flash.php';
include '../includes/generation_objet.php'; 

// Vérifier si l'utilisateur est un gardien ou un chef
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['chef', 'gardien'])) {
    header("Location: ../index.php");
    exit();
}


// Vérifier si un prisonnier tente de soudoyer
if (!isset($_GET['prisonnier']) || !is_numeric($_GET['prisonnier'])) {
    setFlashMessage("Erreur : Prisonnier non valide.", "error");
    header("Location: surveillance_cellules.php");
    exit();
}

$prisonnier_id = intval($_GET['prisonnier']);

// Récupérer les informations du prisonnier
$sql = "SELECT u.nom, u.prenom FROM utilisateur u 
        INNER JOIN prisonnier p ON u.id = p.utilisateur_id
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$prisonnier_id]);
$prisonnier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prisonnier) {
    setFlashMessage("Prisonnier introuvable.", "error");
    header("Location: surveillance_cellules.php");
    exit();
}

// Traitement de la décision du gardien
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accepter'])) {
        setFlashMessage("🤑 Vous avez accepté le pot-de-vin et laissé passer l'infraction.", "success");
        header("Location: surveillance_cellules.php");
        exit();
    } elseif (isset($_POST['refuser'])) {
        // Informer le prisonnier qu'il a été dénoncé
        setFlashMessage("🚨 Vous avez signalé l'infraction. Le prisonnier a été informé.", "error");

        // Rediriger vers la gestion des infractions en passant l'ID du prisonnier
        header("Location: ../views/gestion_infractions.php?prisonnier_id=$prisonnier_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pot-de-vin</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>
<?php displayFlashMessage(); ?>

<div class="container">
    <h2>💰 Tentative de pot-de-vin</h2>
    <p><strong><?= htmlspecialchars($prisonnier['nom']) ?> <?= htmlspecialchars($prisonnier['prenom']) ?></strong> tente de vous soudoyer.</p>
    
    <form method="POST">
        <button type="submit" name="accepter" class="btn success">✅ Accepter le pot-de-vin</button>
        <button type="submit" name="refuser" class="btn danger">🚨 Signaler au chef</button>
    </form>

    <br>
    <a href="surveillance_cellules.php" class="btn">🏠 Retour</a>
</div>

</body>
</html>
