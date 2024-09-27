<?php
session_start();

$userId = $_SESSION['userId'];

// Vérifiez si l'utilisateur est connecté
if (!isset($userId)) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}