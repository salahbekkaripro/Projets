<?php
session_start();
include '../config/db.php';
include '../config/flash.php';

// Vérifier si l'utilisateur est Admin (chef) ou Gardien
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['chef', 'gardien'])) {
    header("Location: ../index.php");
    exit();
}

// Fonction pour récupérer le nom du prisonnier
function getPrisonnierNom($pdo, $prisonnier_id) {
    $stmt = $pdo->prepare("SELECT u.nom, u.prenom FROM utilisateur u 
                           INNER JOIN prisonnier p ON u.id = p.utilisateur_id 
                           WHERE p.id = ?");
    $stmt->execute([$prisonnier_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 🔍 Si on fouille un prisonnier
if (isset($_GET['fouiller']) && is_numeric($_GET['fouiller'])) {
    $prisonnier_id = intval($_GET['fouiller']);

    // Récupérer le nom et prénom du prisonnier
    $prisonnier = getPrisonnierNom($pdo, $prisonnier_id);
    $nom_prenom = $prisonnier ? "{$prisonnier['nom']} {$prisonnier['prenom']}" : "Prisonnier inconnu";

    // Récupérer les objets du prisonnier
    $sql_objets = "SELECT nom_objet, description, interdit FROM objets_prisonniers WHERE prisonnier_id = ?";
    $stmt_objets = $pdo->prepare($sql_objets);
    $stmt_objets->execute([$prisonnier_id]);
    $objets_prisonnier = $stmt_objets->fetchAll(PDO::FETCH_ASSOC);

    if (empty($objets_prisonnier)) {
        setFlashMessage("✅ Le prisonnier $nom_prenom n'a rien d'illégal.", "success", $prisonnier_id);
        header("Location: surveillance_cellules.php");
        exit();
    } else {
        // Vérifier si des objets interdits sont trouvés
        $objets_interdits = array_filter($objets_prisonnier, function($obj) {
            return $obj['interdit'] == 1;
        });

        if (!empty($objets_interdits)) {
            setFlashMessage("❌ Le prisonnier $nom_prenom possède des objets interdits !", "error", $prisonnier_id);
            header("Location: pot_de_vin.php?prisonnier=$prisonnier_id");
            exit();
        }
    }
}

// 💰 Si le prisonnier propose un pot-de-vin
if (isset($_GET['soudoyer']) && is_numeric($_GET['soudoyer'])) {
    $prisonnier_id = intval($_GET['soudoyer']);
    $prisonnier = getPrisonnierNom($pdo, $prisonnier_id);
    $nom_prenom = $prisonnier ? "{$prisonnier['nom']} {$prisonnier['prenom']}" : "Prisonnier inconnu";

    setFlashMessage("💰 Le prisonnier $nom_prenom propose un pot-de-vin !", "info", $prisonnier_id);
    header("Location: pot_de_vin.php?prisonnier=$prisonnier_id");
    exit();
}

// 🚨 Gestion de la décision du gardien (refus du pot-de-vin)
if (isset($_GET['refuser']) && is_numeric($_GET['refuser'])) {
    $prisonnier_id = intval($_GET['refuser']);
    $prisonnier = getPrisonnierNom($pdo, $prisonnier_id);
    $nom_prenom = $prisonnier ? "{$prisonnier['nom']} {$prisonnier['prenom']}" : "Prisonnier inconnu";

    setFlashMessage("🚨 Vous avez signalé l'infraction du prisonnier $nom_prenom.", "error", $prisonnier_id);

    // Rediriger vers la gestion des infractions
    header("Location: ../views/gestion_infractions.php?prisonnier_id=$prisonnier_id");
    exit();
}

// 📌 Récupérer les cellules et les prisonniers associés
$sql = "SELECT c.id AS cellule_id, 
       c.numero_cellule AS cellule_nom, 
       p.id AS prisonnier_id, 
       u.nom AS prisonnier_nom, 
       u.prenom AS prisonnier_prenom
        FROM cellule c
        LEFT JOIN prisonnier p ON c.id = p.cellule_id
        LEFT JOIN utilisateur u ON p.utilisateur_id = u.id
        WHERE u.role = 'prisonnier'
        ORDER BY c.id";

$stmt = $pdo->query($sql);
$cellules = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cellules[$row['cellule_id']]['nom'] = $row['cellule_nom'];
    if (!empty($row['prisonnier_id'])) {
        $cellules[$row['cellule_id']]['prisonniers'][] = [
            'id' => $row['prisonnier_id'],
            'nom' => $row['prisonnier_nom'],
            'prenom' => $row['prisonnier_prenom']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Surveillance des Cellules</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- Affichage du message flash -->
<?php displayFlashMessage(); ?>

<div class="container">
    <h2>🔍 Surveillance des Cellules</h2>

    <?php foreach ($cellules as $cellule_id => $cellule) : ?>
        <div class="cellule">
            <h3>Cellule : <?= htmlspecialchars($cellule['nom']) ?></h3>
            <ul>
                <?php if (!empty($cellule['prisonniers'])) : ?>
                    <?php foreach ($cellule['prisonniers'] as $prisonnier) : ?>
                        <li>
                            <?= htmlspecialchars($prisonnier['nom']) ?> <?= htmlspecialchars($prisonnier['prenom']) ?>
                            <a href="surveillance_cellules.php?fouiller=<?= $prisonnier['id'] ?>" class="btn">🔍 Fouiller</a>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li>Aucun prisonnier dans cette cellule.</li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endforeach; ?>

    <a href="../index.php" class="btn">🏠 Retour au tableau de bord</a>
</div>

</body>
</html>
