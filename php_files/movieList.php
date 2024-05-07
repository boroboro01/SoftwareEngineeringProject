<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th.title,
        td.title {
            width: 50%;

        }

        th.genres,
        td.genres {
            width: 50%;

        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid black;
            text-decoration: none;
            color: black;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>

</head>

<body>
    <h1>Movie List</h1>
    <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1234";
    $dbname = "movielens";
    $conn = new mysqli($servername, $username, "", $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $results_per_page = 10;
    $page = 1;
    if (isset($_GET["page"])) {
        $page = intval($_GET["page"]);
    }
    $offset = ($page - 1) * $results_per_page;

    $sql = "SELECT COUNT(*) AS total FROM movies";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);

    $sql = "SELECT movieId, title, genres FROM movies LIMIT $results_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th class=\"title\">Title</th><th class=\"genres\">Genres</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td class=\"title\"><a href='movieDetails.php?movieId=" . $row['movieId'] .
                "'>" . htmlspecialchars($row["title"]) .
                "</a></td><td class=\"genres\">" . htmlspecialchars($row["genres"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    $num_links = 10;
    $start = max(1, $page - ($num_links / 2));
    $end = min($total_pages, $start + $num_links - 1);

    echo "<div class='pagination'>";
    for ($i = $start; $i <= $end; $i++) {
        echo "<a href='?page=$i'" . ($page == $i ? " class='active'" : "") . ">$i</a>";
    }
    echo "</div>";

    $conn->close();
    ?>
</body>

</html>