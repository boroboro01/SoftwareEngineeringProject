<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body>div {
            background-color: black;
            height: 100%;
            color: white;
        }

        header {
            float: right;
            width: 90%;
            height: 10%;
        }

        nav {
            position: fixed;
            float: left;
            background-color: rgb(40, 40, 40);
            width: 0%;
            height: 100%;
            transition: width 1s;
        }

        #menu_icon {
            background-color: white;
            /*메뉴 아이콘 안보여서 임시 배경색 설정*/
            width: 40px;
            height: 40px;
            margin-left: 10px;
            margin-top: 10px;
        }

        .sidemenu {
            height: 50px;
            overflow: hidden;
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

        #login_a {
            margin-left: 40px;
            bottom: 10px;
        }

        nav:hover {
            position: fixed;
            background-color: rgb(40, 40, 40);
            float: left;
            width: 150px;
            height: 100%;
        }

        section {
            justify-content: center;
            align-items: center;
            text-align: center;
            float: right;
            width: 90%;
            height: 80%;
        }

        footer {
            float: right;
            width: 90%;
            height: 10%;
        }

        #recommend {
            height: 100%;
        }

        div.recommend_box {
            height: 660px;
        }

        h1.title {
            color: aqua;
            text-align: center;
            margin: 0;
            margin-top: 10px;
            padding: 0;
        }

        h2.subtitle {
            margin-top: 0;
            padding: 0;
        }

        article {
            width: 100%;
        }

        hr.footer_bar {
            width: 95%;
            color: grey;
        }

        #recommending {
            position: absolute;
            float: right;
            bottom: 0;
            right: 0;
            height: 15%;
        }

        #register_a {
            margin-left: 40px;
            bottom: 10px;
        }

        @keyframes imagerotation {}
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