<?php
require_once("pdo.php"); // Inclusion du fichier pour la connexion à la base de données
require_once("init.php"); // Inclusion du fichier pour initialiser la session utilisateur et d'autres paramètres

// Requête SQL pour sélectionner les films favoris de l'utilisateur connecté
$query = "
    SELECT imdbID FROM favorites
    WHERE idUser = :userId
";

// Prépare la requête SQL avec une variable pour l'ID utilisateur (sécurise contre les injections SQL)
$stmt = $pdo->prepare($query);

// Lie l'ID de l'utilisateur connecté à la variable :userId de la requête
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

// Exécute la requête préparée
$stmt->execute();

// Récupère tous les résultats sous forme de tableau associatif
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourne les résultats sous forme de JSON pour que le front-end puisse les utiliser
echo json_encode($favorites);
?>
