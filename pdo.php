<?php
require_once __DIR__ . '/vendor/autoload.php'; // Charger l'autoloader de Composer

// Charger les variables d'environnement depuis le fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Infos de connexions récupérées depuis le fichier .env
$host = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

// Tentative de connexion
try {   
    $pdo = new PDO($host, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Il y a une erreur : " . $e->getMessage();
}
?>
