<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie List</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid white;
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
            color: white;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: black;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        @keyframes imagerotation { 
        }
    </style>

</head>

<body>
    <header>
        <h1>Movie List</h1>
    </header>
    <nav>
        <img src="../Assets/Icons/menu.png" alt="메뉴 아이콘" id="menu_icon">
            <ul>
                <li class="sidemenu"><a href="./main.html">Trending</a></li>
                <li class="sidemenu"><a href="http://">Favorite</a></li>
                <li class="sidemenu"><a href="http://">History</a></li>
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
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search for movies...">
        <button type="submit">Search</button>
    </form>
    <section>
    <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1234";
    $dbname = "movielens";
    $conn = new mysqli($servername, $username, "", $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $results_per_page = 10;
    $page = 1;
    if (isset($_GET["page"])) {
        $page = intval($_GET["page"]);
    }
    $offset = ($page - 1) * $results_per_page;

    // Count the total number of results
    $sql = "SELECT COUNT(*) AS total FROM movies WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);

    // Retrieve the results for the current page
    $sql = "SELECT movieId, title, genres FROM movies WHERE title LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $searchTerm, $results_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table><tr><th class='title'>Title</th><th class='genres'>Genres</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td class='title'><a href='movieDetails.php?movieId=" . $row['movieId'] .
                "'>" . htmlspecialchars($row["title"]) .
                "</a></td><td class='genres'>" . htmlspecialchars($row["genres"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results found";
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
    </section>
</body>

</html>