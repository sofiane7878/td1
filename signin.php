<?php
require_once('pdo.php'); // Connexion à la base de données

if (isset($_POST["submit"])) {
    $pseudo = htmlspecialchars(trim($_POST["pseudo"]));
    $password = $_POST["password"];

    if (!empty($pseudo) && !empty($password)) {
        // Récupération de l'utilisateur via le pseudo
        $sql = 'SELECT id, pseudo, password FROM user WHERE pseudo = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            $userId = $user["id"];
            require_once("init.php");
            header("Location: index.php");
            exit;
        } else {
            echo "<p>Identifiants incorrects.</p>";
        }
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à Filmas</title>
</head>
<body>
    <h1>Connexion à Filmas</h1>
    <form action="" method="post">
        <label for="pseudo">Pseudo:</label>
        <input id="pseudo" name="pseudo" type="text" required>
        
        <label for="password">Mot de passe:</label>
        <input id="password" name="password" type="password" required>
        
        <button name="submit" type="submit">Se connecter</button>
    </form>
</body>
</html>
