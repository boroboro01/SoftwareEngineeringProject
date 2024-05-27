<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="../css_files/main_style.css">
</head>

<body>
    <div>
        <header>
            <h1 class="title">SEENEMA</h1>
        </header>
        <nav>
            <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
            <ul>
                <li class="sidemenu"><a href="./main.html">Trending</a></li>
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
                <div class="recommend_box">
                    <a href=""><img src="../Assets/Images/Posters/elemental.webp" alt="영화 포스터" id="recommend"></a>

                </div>
            </article>
        </section>
        <footer>
            <hr class="footer_bar">
            <a href=""><img src="../Assets/Images/Vectors/magnifier_animal_inu.png" alt="영화 추천 받기"
                    id="recommending"></a>
        </footer>
    </div>
</body>

</html>