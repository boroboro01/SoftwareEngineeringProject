<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
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
            echo "<h1>" . htmlspecialchars($movie['title']) . "</h1>";
            echo "<img src='../Assets/Images/Posters/" . $movieId . ".jpg' alt='" .
                htmlspecialchars($movie['title']) . " Poster' style='width:200px;'>";
            echo "<p>Average Rating: " . round($movie['average_rating'], 2) . "</p>";
        } else {
            echo "<p>No movie found.</p>";
        }

        $sql = "SELECT tag FROM tags WHERE movieId = $movieId";
        $tagsResult = $conn->query($sql);
        if (!$tagsResult) {
            die("SQL error: " . $conn->error);
        }

        echo "<p>Tags: ";
        while ($tag = $tagsResult->fetch_assoc()) {
            echo htmlspecialchars($tag['tag']) . " ";
        }
        echo "</p>";
        echo "<a href='movieList.php'>Back to Movie List</a><br>";
        echo "<a href='../html_files/movieComment.php?movieId=" . $movieId . "'>Add rating and tag</a>";
    } else {
        echo "<p>Movie not found.</p>";
    }

    $conn->close();
    ?>
</body>

</html>