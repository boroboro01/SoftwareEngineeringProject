<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="../css_files/main_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #010101;
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
            background-color: #010101;
            width: 0;
            height: 100%;
            overflow: hidden;
            transition: width 300ms;
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
            background: linear-gradient(#272122, #010101);
            width: 70vw;
            padding: 20px 30px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 25);
        }

        .movie h3 {
            font-size: 1em;
            text-align: center;
        }

        .movie p {
            font-size: 1em;
            text-align: center;
        }

        section {
            margin-left: 70px;
            padding: 20px;
        }

        article {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            background: linear-gradient(#272122, #0d1423);
            width: 90vw;
            height: 100%;
        }

        article .subtitle {
            text-align: center;
            width: 100%;
            height: 10vh;
            font-size: 3em;
            font-weight: 600;
        }

        article .subtitle p {
            text-shadow: 0 15px 10px rgba(0, 0, 0, 25);
        }
        .rating {
            display: flex;
            justify-content: center;
        }
        .rating i {
            margin-left: 5px;
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
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
            <div id="login_a" class="sidemenu">
                <a href="./logout.php">Log out</a>
            </div>
            <div id="username" class="sidemenu">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
        <?php else : ?>
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
            <div class="subtitle">
                <p>Trending</p>
            </div>
            <div id="trendingMovies" class="movie-container">
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    fetch('trendingMovies.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                var trendingMoviesDiv = document.getElementById('trendingMovies');
                                trendingMoviesDiv.innerHTML = ''; // 기존 내용을 지움

                                data.movies.forEach(movie => {
                                    var movieDiv = document.createElement('div');
                                    var ratingRound = Math.round(movie.average_rating);
                                    var rating = document.createElement("div");
                                    var star = document.createElement("div");
                                    rating.classList.add("rating");
                                    star.innerHTML = "<i class='fa-solid fa-star'></i>"
                                    movieDiv.classList.add('movie');
                                    movieDiv.innerHTML = `
                                        <h3>${movie.title}</h3>
                                        <img src='../Assets/Images/Posters/${movie.movieId}.jpg' alt='${movie.title} Poster'>
                                        <p>Rating Count: ${movie.rating_count}</p>
                                    `;
                                    console.log(ratingRound);
                                    for(let i=0;i<ratingRound;i++) {
                                        rating.insertBefore(star.cloneNode(true), null);
                                    }
                                    trendingMoviesDiv.appendChild(movieDiv);
                                    movieDiv.appendChild(rating);
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
        <a href="../html_files/recommendation.html"><img src="../Assets/Images/Vectors/magnifier_animal_inu.png" alt="영화 추천 받기" id="recommending"></a>
    </footer>
</body>

</html>