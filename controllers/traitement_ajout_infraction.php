<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prisonnier_id = $_POST['prisonnier_id'];
    $type_infraction = $_POST['type_infraction'];
    $date_infraction = $_POST['date_infraction'];
    $sanction = !empty($_POST['sanction']) ? $_POST['sanction'] : null;

    $sql = "INSERT INTO infraction (prisonnier_id, type_infraction, date_infraction, sanction)
            VALUES (:prisonnier_id, :type_infraction, :date_infraction, :sanction)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':prisonnier_id' => $prisonnier_id,
        ':type_infraction' => $type_infraction,
        ':date_infraction' => $date_infraction,
        ':sanction' => $sanction
    ]);

    header("Location: ../views/gestion_infractions.php");
    exit();
}
?>
