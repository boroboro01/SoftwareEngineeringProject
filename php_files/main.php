<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="../css_files/main_style.css">
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
        }

        .title {
            text-align: center;
            margin: 20px 0;
        }

        #menu_icon {
            position: fixed;
            left: 10px;
            top: 10px;
            width: 40px;
            height: 40px;
            z-index: 2;
        }

        nav {
            position: fixed;
            left: 0;
            top: 0;
            background-color: rgb(40, 40, 40);
            width: 0;
            height: 100%;
            overflow: hidden;
            transition: width 0.5s;
            z-index: 1;
        }

        .sidemenu {
            height: 50px;
            overflow: hidden;
            text-align: center;
            padding-top: 10px;
        }

        .sidemenu>a {
            white-space: nowrap;
            text-decoration: none;
            color: white;
        }

        .sidemenu>a:hover {
            text-decoration: underline;
            color: white;
        }

        #menu_icon:hover+nav,
        nav:hover {
            width: 150px;
        }

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

        section {
            margin-left: 70px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1 class="title">SEENEMA</h1>
    </header>
    <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
    <nav>
        <ul>
            <li class="sidemenu"><a href="../php_files/favorite.php">Favorite</a></li>
            <li class="sidemenu"><a href="../php_files/history.php">History</a></li>
            <li class="sidemenu"><a href="../php_files/movieList.php">Movie List</a></li>
        </ul>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
            <div id="login_a" class="sidemenu">
                <a href="./logout.php">Log out</a>
            </div>
            <div id="username" class="sidemenu">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
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
            <h2 class="subtitle">Trending</h2>
            <div id="trendingMovies" class="movie-container"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
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
        </article>
    </section>
    <footer>
        <hr class="footer_bar">
        <a href="recommend.php"><img src="../Assets/Images/Vectors/magnifier_animal_inu.png" alt="영화 추천 받기"
                id="recommending"></a>
    </footer>
</body>

</html>