<?php
require_once('pdo.php'); // Inclusion de la connexion à la base de données via PDO

// Vérification si le formulaire a été soumis
if (isset($_POST["submit"])) {  
    // Vérifie que tous les champs du formulaire sont remplis
    if (!empty($_POST["pseudo"]) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm'])) {

        // Récupération des données du formulaire
        $pseudo = $_POST["pseudo"];
        $email = $_POST["email"];
        $password = $_POST["password"];    
        $password_confirmation = $_POST["confirm"];

        // Hashage sécurisé du mot de passe
        $password_secure = password_hash($password, PASSWORD_DEFAULT);

        // Sécurisation du pseudo en neutralisant les caractères spéciaux (protection XSS)
        $pseudo = htmlspecialchars($pseudo);

        // Validation du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {   
            $error = "L'email n'est pas valide"; // Message d'erreur si l'email est incorrect
            exit;
        }

        // Vérifie si les mots de passe correspondent
        if ($password !== $password_confirmation) {
            $message = "Les mots de passe sont différents."; // Message d'erreur si les mots de passe ne sont pas identiques
            exit;
        }

        try { 
            // Préparation de la requête SQL pour insérer un nouvel utilisateur
            $sql = 'INSERT INTO user (pseudo, email, password) VALUES (?, ?, ?) ';
            $stmt = $pdo->prepare($sql); // Prépare la requête pour éviter les injections SQL
            $stmt->execute([$pseudo, $email, $password_secure]); // Exécute la requête avec les données utilisateur

            // Redirection vers la page de connexion après l'inscription réussie
            header("Location: signin.php");
            exit;
        } catch(PDOException $e) {
            // Gestion des erreurs liées à l'exécution de la requête SQL
            $error = $e->getMessage();
            echo $error;
        }
    }    
}        
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur filmas</title>
</head>
<body>

        <form action="" method="post">
            <input name="pseudo" type="text" placeholder="pseudo">
            <input name="email" type="email" placeholder="email">
            <input name="password" type="password" placeholder="password">
            <input name="confirm" type ="password" placeholder="confirm">
            <button name="submit" type="submit">Valider</button>
        </form>

        <a href="signin.php">Vous avez déjà un compte ? Connectez-vous</a>
    
</body>
</html>

