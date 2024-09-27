// Récupère la valeur du champ de recherche
const searchInput = document.getElementById('searchInput').value.trim(); 
const resultDiv = document.getElementById('result'); // Sélectionne l'élément où afficher les résultats

// Écouteur d'événement pour le bouton Favoris
document.querySelector('.favBtn').addEventListener('click', async () => {
    resultDiv.document.innerHTML = ""; // Efface les résultats actuels
    await loadFavorites(); // Charge les favoris
});

// Écouteur d'événement pour le formulaire de recherche
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche le rechargement de la page par défaut

    const searchInput = document.getElementById('searchInput').value.trim(); // Récupère la valeur du champ de recherche
    const resultDiv = document.getElementById('result'); // Sélectionne l'élément de résultat

    // Vérifie si le champ de recherche est vide
    if (searchInput === '') {
        resultDiv.innerHTML = '<p>Veuillez entrer un titre de film.</p>'; // Affiche un message d'erreur
        return;
    }

    // API OMDB pour rechercher des films via un titre
    const apiKey = 'f0888528'; 
    const apiUrl = `https://www.omdbapi.com/?s=${encodeURIComponent(searchInput)}&apikey=${apiKey}`; // Génère l'URL de l'API

    // Requête à l'API OMDB pour récupérer les films
    fetch(apiUrl)
        .then(response => response.json()) // Parse la réponse en JSON
        .then(data => {
            if (data.Response === "True") {
                displayMovies(data.Search); // Si les films sont trouvés, les afficher
            } else {
                resultDiv.innerHTML = '<p>Aucun film trouvé pour cette recherche.</p>'; // Message d'erreur si aucun film n'est trouvé
            }
        })
        .catch(error => {
            console.error('Erreur:', error); // Gérer les erreurs de requête
            resultDiv.innerHTML = '<p>Une erreur est survenue lors de la recherche.</p>'; // Affiche un message d'erreur si la requête échoue
        });
});

// Fonction pour afficher les films recherchés
function displayMovies(movies) {
    const resultDiv = document.getElementById('result'); // Sélectionne la div des résultats
    resultDiv.innerHTML = ''; // Vide la div des résultats actuels

    // Parcours de chaque film trouvé et affichage dans la div
    movies.forEach(movie => {
        const movieHTML = `
            <div class="movie">
                <h3>${movie.Title} (${movie.Year})</h3>
                <img src="${movie.Poster !== 'N/A' ? movie.Poster : 'placeholder.jpg'}" alt="${movie.Title}">
                <p>ID IMDB : ${movie.imdbID}</p>
                <button onclick="addToFavorites('${movie.imdbID}', '${movie.Title}', '${movie.Year}')">Ajouter aux favoris</button>
            </div>`;
        resultDiv.innerHTML += movieHTML; // Ajoute le HTML du film à la div des résultats
    });
}

// Fonction pour ajouter un film aux favoris
function addToFavorites(imdbID) {
    const xhr = new XMLHttpRequest(); // Crée une nouvelle requête XMLHttpRequest
    xhr.open('POST', 'add_favorite.php', true); // Prépare une requête POST vers le fichier PHP
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Déclare l'en-tête pour l'encodage des données
    xhr.send(`imdbID=${imdbID}`); // Envoie l'ID du film au serveur

    // Gère la réponse du serveur
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText); // Parse la réponse en JSON
            if (response.status === 'success') {
                alert('Film ajouté aux favoris !'); // Alerte succès
            } else {
                alert('Erreur lors de l\'ajout aux favoris.'); // Alerte en cas d'erreur
            }
        }
    };
}

// Fonction pour charger les favoris depuis le serveur
async function loadFavorites() {
    try {
        const response = await fetch('get_fav.php'); // Requête pour récupérer les favoris
        const favorites = await response.json(); // Parse la réponse en JSON
        displayFavorites(favorites); // Affiche les favoris
    } catch (error) {
        console.error('Erreur lors du chargement des favoris:', error); // Gestion des erreurs
        document.getElementById('favorites').innerHTML = '<p>Erreur lors du chargement des favoris.</p>'; // Affiche un message d'erreur
    }
}

// Fonction pour afficher les favoris
function displayFavorites(favorites) {
    const favoritesDiv = document.getElementById('favorites'); // Sélectionne la div des favoris
    favoritesDiv.innerHTML = ''; // Vide la div des favoris actuels

    // Vérifie si l'utilisateur n'a pas de favoris
    if (favorites.length === 0) {
        favoritesDiv.innerHTML = '<p>Vous n\'avez aucun film en favoris pour le moment.</p>'; // Message si aucun favori
    } else {
        // Parcours de chaque favori et affichage dans la div
        favorites.forEach(favorite => {
            fetch(`https://www.omdbapi.com/?i=${favorite.imdbID}&apikey=f0888528`) // Requête pour chaque favori
                .then(response => response.json()) // Parse la réponse
                .then(data => {
                    if (data.Response === "True") {
                        const movieHTML = `
                            <div class="movie">
                                <h3>${data.Title} (${data.Year})</h3>
                                <img src="${data.Poster !== 'N/A' ? data.Poster : 'placeholder.jpg'}" alt="${data.Title}">
                                <p>ID IMDB : ${data.imdbID}</p>
                                <button onclick="removeFavorite('${favorite.imdbID}')">Retirer des favoris</button>
                            </div>`;
                        favoritesDiv.innerHTML += movieHTML; // Ajoute le film à la div des favoris
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error); // Gestion des erreurs
                    favoritesDiv.innerHTML = '<p>Erreur lors du chargement des favoris.</p>'; // Affiche un message d'erreur
                });
        });
    }
}

// Fonction pour retirer un film des favoris
function removeFavorite(imdbID) {
    // Envoie une requête pour retirer un film des favoris
    fetch('remove_fav.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded' // Type de contenu
        },
        body: `id=${encodeURIComponent(imdbID)}` // Envoie l'ID du film
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de la suppression du favori'); // Gère les erreurs
        }
        return response.json(); // Parse la réponse
    })
    .then(data => {
        alert('Film retiré des favoris !'); // Alerte succès
        loadFavorites(); // Recharge les favoris après suppression
    })
    .catch(error => {
        console.error('Erreur:', error); // Gestion des erreurs
    });
}
