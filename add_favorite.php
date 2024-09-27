<?php
require_once("pdo.php");
require_once("init.php");

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$userId = $_SESSION['userId'];

// Vérifie si les données ont été envoyées via la méthode POST
if (isset($_POST['imdbID'])) {
    $imdbID = $_POST['imdbID'];

    // Prépare la requête d'insertion pour ajouter le film aux favoris
    $query = "INSERT INTO favorites (idUser, imdbID) VALUES (:userId, :imdbID)";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':imdbID', $imdbID, PDO::PARAM_STR);
//Exécute la requête
    try {
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Film ajouté aux favoris !']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout aux favoris']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aucune donnée reçue']);
}
?>
