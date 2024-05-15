<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
    <style>
        html, body { margin: 0; padding: 0; height: 100%; }

        body {
            background-color: black;
            height: 100%;
            color: white;
        }

        header {
            text-align: center;
            float: right;
            width: 90%;
            height: 10%;
        }

        nav {
            margin: 0; padding: 0;
            position: fixed;
            float: left;
            background-color: rgb(40, 40, 40);
            width: 0%;
            height: 100%;
            transition: width 1s;
        }

        #menu_icon{
            width: 40px;
            height: 40px;
            margin-left: 10px;
            margin-top: 10px;
        }
        .sidemenu {
            height: 50px;
            overflow: hidden;
        }
        .sidemenu > a {
            white-space: nowrap;
            text-decoration: none;
            color: white;
        }
        .sidemenu>a:hover {
            text-decoration:underline;
            color: white;
        }
        nav:hover{
            position: fixed;
            background-color: rgb(40, 40, 40);
            float: left;
            width: 150px;
            height: 100%;
        }
        .sizedbox_large {
            height: 55%;
        }

        section {
            padding: 10px;
            justify-content: center;
            align-items: center;
            text-align: center;
            float: right;
            width: 90%;
            height: 80%;
        }

        .infobox{
            width: 100%;
            height: 100px;

        }

        .btnbox {
        }

        .rating {
            display: inline-block;
        }

        .Taging {
            display: inline-block;
            padding-left: 50px;
        }

        .btn {
            padding-top: 10px;
            text-align: center;
            width: 150px;
            height: 36px;
            background-color: white;
            display: block;
            text-decoration: none;
            color: black;
            border-radius: 20px;
        }

        .back_btn {
            display: inline-block;
        }

        .add_btn {
            display: inline-block;
        }

        @keyframes imagerotation { 
        }
    </style>
</head>

<body>
    <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1234";
    $dbname = "movielens";
    $conn = new mysqli($servername, $username, "", $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['movieId'])) {
        $movieId = intval($_GET['movieId']);


        $sql = "SELECT m.title, AVG(r.rating) AS average_rating FROM ratings r JOIN movies m ON r.movieId = m.movieId WHERE r.movieId = $movieId GROUP BY m.title";
        $result = $conn->query($sql);
        if (!$result) {
            die("SQL error: " . $conn->error);
        }

        if ($movie = $result->fetch_assoc()) {
            echo "<header><h1>" . htmlspecialchars($movie['title']) . "</h1><header>";
            echo "<nav>
            <img src=\"../Assets/Icons/menu.png\" alt=\"메뉴 아이콘\" id=\"menu_icon\">
            <ul>
                <li class=\"sidemenu\"><a href=\"./main.html\">Trending</a></li>
                <li class=\"sidemenu\"><a href=\"http://\">Favorite</a></li>
                <li class=\"sidemenu\"><a href=\"http://\">History</a></li>
                <div id=\"movieList_a\" class=\"sidemenu\">
                    <a href=\"../php_files/movieList.php\">Movie List</a>
                </div>
            </ul>
            </nav>";
            echo "<section><div class=\"infobox\"><p>Average Rating: " . round($movie['average_rating'], 2) . "</p>";
        } else {
            echo "<header><p>No movie found.</p><header>";
        }

        $sql = "SELECT tag FROM tags WHERE movieId = $movieId";
        $tagsResult = $conn->query($sql);
        if (!$tagsResult) {
            die("SQL error: " . $conn->error);
        }

        echo "<p class=\"Taging\">Tags: ";
        while ($tag = $tagsResult->fetch_assoc()) {
            echo htmlspecialchars($tag['tag']) . " ";
        }
        echo "</p></div>";
        echo "<div class=\"btnbox\"><a href='../html_files/movieComment.php?movieId=" . $movieId . "'>Add rating and tag</a>";
        echo "<a href='movieList.php'>Back to Movie List</a></div>";
    } else {
        echo "<p>Movie not found.</p>";
    }

    $conn->close();
    ?>
</body>

</html>