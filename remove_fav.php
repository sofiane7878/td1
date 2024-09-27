<?php
require_once("pdo.php");
require_once('init.php');

// Récupération de l'ID du film envoyé par la requête POST
if (isset($_POST['id'])) {
    $imdbID = htmlspecialchars(trim($_POST['id']));

    // Préparation et exécution de la requête SQL pour supprimer le favori
    $sql = "DELETE FROM favorites WHERE imdbID = ? AND idUser = ?";
    $stmt = $pdo->prepare($sql);
    
    // Exécuter la requête avec les bonnes valeurs
    if ($stmt->execute([$imdbID, $userId])) {
        echo json_encode(['status' => 'success', 'message' => 'Favori supprimé avec succès']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression du favori']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID du film non fourni']);
}
?>