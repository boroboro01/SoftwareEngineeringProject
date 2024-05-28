<!DOCTYPE html>
<html lang="en">

<?php
require_once '../php_files/db.php';
$movieId = $_GET['best_movie_id'];

// 영화 아이디에 대한 태그와 유저 정보를 가져오는 쿼리
$sql = "SELECT t.tag, t.userId, m.title 
        FROM tags t 
        JOIN movies m ON t.movieId = m.movieId 
        WHERE t.movieId = $movieId";
$result = $conn->query($sql);

$sql = "SELECT title 
        FROM movies
        WHERE movieId = $movieId";
$result2 = $conn->query($sql);
$row2 = $result2->fetch_assoc()
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main</title>
    <link rel="stylesheet" href="../css_files/main_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css_files/recommendPanel_style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Teachers', sans-serif;
        }
        body {
            /* display: flex;
            justify-content: center;
            align-items: center; */
            min-height: 100vh;
            background: linear-gradient(#9c101e, #0d1423);
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .comment_button {
            margin-left: 35px;
        }
        .comment_button a {
            text-decoration: none;
            color: #010101;
            transition: 300ms ease;
        }
        .comment_button:hover a{
            color: #fff;
        }
        #username p{
            color: #fff;
        }
    </style>
</head>


<body>
    <header>
        <h1 class="title">SEENEMA</h1>
    </header>
    <nav>
        <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
        <div class="sidebar">
            <ul>
            <li class="sidemenu"><a href="../php_files/main.php">home</a></li>
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
        </div>
    </nav>
    <section>
        <div class="panel">
            <div class="content">
                <img class="movie_poster" src='../Assets/Images/Posters/<?php echo $movieId; ?>.jpg'
                    alt='<?php echo htmlspecialchars($movieId); ?> Poster'>
            </div>
            <div class="column">
                <div class="content_right">
                    <div class="panel_title"><?php echo htmlspecialchars($row2["title"]); ?></div>
                    <?php
                    // 태그와 유저 정보를 기반으로 코멘트 생성
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='comment'>
                                    <i class='fa-regular fa-user'></i>
                                    <div class='name'>" . htmlspecialchars($row['userId']) . "</div>
                                    <div class='reply'>" . htmlspecialchars($row['tag']) . "</div>
                                </div>";
                        }
                    } else {
                        echo "<div class='comment'>
                                <i class='fa-regular fa-user'></i>
                                <div class='name'>No comments</div>
                                <div class='reply'>There are no comments for this movie yet.</div>
                            </div>";
                    }
                    ?>
                </div>
                <div class="comment_button" id="comment_button">
                    <a href="../php_files/movieDetails.php?movieId=<?php echo $movieId; ?>">Details</a>
                </div>
            </div>
        </div>
    </section>
    <footer>
    </footer>
</body>

</html>