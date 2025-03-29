<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "0";
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE utilisateur_id = ? AND lu = 0");
$stmt->execute([$user_id]);
echo $stmt->fetchColumn();
?>
