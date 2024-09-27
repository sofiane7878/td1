<?php

require_once('init.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TD</title>
    <link rel="stylesheet" href="style.css">
    <script src=https://unpkg.com/axios/dist/axios.min.js></script>
    <script src="test.js" defer></script>
</head>
<body>
    <h1>Premier TD</h1>
    

    <div class="index">
        <form class="form">
            <input type="text" id="searchInput" class="searchInput" placeholder="Votre recherche ici...">
            <button type="submit" class="searchBtn">Rechercher</button>
            <button type="button" class="favBtn">Favoris</button>
        </form>
    </div>

    <div class="result" id="result"></div>
    <div class="favorites" id="favorites"></div>
</body>
</html>

