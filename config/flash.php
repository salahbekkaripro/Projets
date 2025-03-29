<?php

function setFlashMessage($message, $type = 'success', $utilisateur_id = null) {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];

    // Insérer la notification en base si un utilisateur est concerné
    if ($utilisateur_id) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO notifications (utilisateur_id, message, type) VALUES (?, ?, ?)");
        $stmt->execute([$utilisateur_id, $message, $type]);
    }
}

// Fonction pour afficher et supprimer le message flash
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        echo "<div class='flash-message {$msg['type']}'>{$msg['message']}</div>";
        unset($_SESSION['flash_message']); // Supprimer après affichage
    }
}
?>