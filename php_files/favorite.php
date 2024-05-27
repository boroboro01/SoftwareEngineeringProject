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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        header {
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

        section {
            margin-left: 70px;
            padding: 20px;
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
    </style>
</head>

<body>
    <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
    <nav>
        <ul>
            <li class="sidemenu"><a href="./main.php">Home</a></li>
            <li class="sidemenu"><a href="#">Favorite</a></li>
            <li class="sidemenu"><a href="./history.php">History</a></li>
            <li class="sidemenu"><a href="../php_files/movieList.php">Movie List</a></li>
        </ul>
    </nav>
    <section>
        <h1>Your Favorite Movies</h1>
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
    </section>
</body>

</html>