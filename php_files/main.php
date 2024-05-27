<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="../css_files/main_style.css">
    <style>
        .movie-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .movie {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .movie img {
            width: 150px;
            height: 225px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .movie h3 {
            font-size: 1.5em;
            text-align: center;
        }

        .movie p {
            font-size: 1.2em;
            text-align: center;
        }

        #trendButton {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div>
        <header>
            <h1 class="title">SEENEMA</h1>
        </header>
        <nav>
            <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
            <ul>
                <li class="sidemenu"><a href="../php_files/favorite.php">Favorite</a></li>
                <li class="sidemenu"><a href="../php_files/history.php">History</a></li>
                <div id="movieList_a" class="sidemenu">
                    <a href="../php_files/movieList.php">Movie List</a>
                </div>
            </ul>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                <div id="login_a" class="sidemenu">
                    <a href="./logout.php">Log out</a>
                </div>
                <div id="username" class="sidemenu">
                    <p> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
            <?php else: ?>
                <div id="login_a" class="sidemenu">
                    <a href="../html_files/login.html">Log in</a>
                </div>
                <div id="register_a" class="sidemenu">
                    <a href="../html_files/register.html">Register</a>
                </div>
            <?php endif; ?>
        </nav>
        <section>
            <article>
                <h2 class="subtitle">Treding</h2>
                <button id="trendButton">Show Trending Movies</button>
                <div id="trendingMovies" class="movie-container"></div>

                <script>
                    document.getElementById('trendButton').addEventListener('click', function () {
                        fetch('trendingMovies.php')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    var trendingMoviesDiv = document.getElementById('trendingMovies');
                                    trendingMoviesDiv.innerHTML = ''; // 기존 내용을 지움

                                    data.movies.forEach(movie => {
                                        var movieDiv = document.createElement('div');
                                        movieDiv.classList.add('movie');
                                        movieDiv.innerHTML = `
                                <h3>${movie.title}</h3>
                                <img src='../Assets/Images/Posters/${movie.movieId}.jpg' alt='${movie.title} Poster'>
                                <p>Average Rating: ${movie.average_rating}</p>
                                <p>Rating Count: ${movie.rating_count}</p>
                            `;
                                        trendingMoviesDiv.appendChild(movieDiv);
                                    });
                                } else {
                                    alert('Failed to load trending movies: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching trending movies:', error);
                            });
                    });
                </script>

    </div>
    </article>
    </section>
    <footer>
        <hr class="footer_bar">
        <a href="recommend.php"><img src="../Assets/Images/Vectors/magnifier_animal_inu.png" alt="영화 추천 받기"
                id="recommending"></a>
    </footer>
    </div>
</body>

</html>