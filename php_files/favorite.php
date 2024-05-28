<?php
session_start();

// 로그인 상태 검증
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You must be logged in to view favorites');
    window.location.href = '../html_files/login.html';</script>";
    exit;
}

require_once 'db.php'; // 데이터베이스 연결 스크립트

$userId = $_SESSION['user_id'];

// 좋아요 누른 영화 목록 조회
$sql = "SELECT m.movieId, m.title, m.genres FROM movies m JOIN favorites f ON m.movieId = f.movie_id WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Favorite Movies</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css_files/main_style.css">
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

        .infobox {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgb(40, 40, 40);
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
        }

        tr:hover {
            background-color: #555;
        }
        .subtitle {
            text-align: center;
            width: 100%;
            height: 10vh;
            font-size: 3em;
            font-weight: 600;
        }

        .subtitle p {
            text-shadow: 0 15px 10px rgba(0, 0, 0, 25);
        }
        #username p{
            color: #fff;
        }
        .content {
            display: flex;
            width: 80vw;
            padding: 20px;
        }
    </style>
</head>

<body>
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
    <div class="wrapper">
        <div class="subtitle">
        <p>Your Favorite Movies</p>
        </div>
        <div class="content">
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Title</th><th>Genres</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['genres']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have no favorite movies yet.</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
    </div>
</body>

</html>