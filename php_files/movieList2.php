<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css_files/movie_card.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Dongle&family=M+PLUS+Rounded+1c&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap');
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




    <!-- <div class="card">
        <div class="poster">
            <img src="../Assets/Images/Posters/1.jpg">
        </div>
        <div class="details">
            <img src="../Assets/Images/Logos/1.png" class="logo">
            <div class="rating">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <span>4/5</span>
            </div>
            <div class="tags">
                <span>Sci-fi</span>
                <span>Comedy</span>
            </div>
            <div class="info">
                <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
            </div>
            <div class="comment">
                <h4>Comment</h4>
                <ul>
                    <li>
                        <i class="fa-regular fa-user"></i>
                        <p>Integer nibh mauris, feugiat sagittis eleifend et, faucibus vel neque.</p>
                    </li>
                    <li>
                        <i class="fa-regular fa-user"></i>
                        <p>Phasellus tincidunt congue dignissim.</p>
                    </li>
                    <li><i class="fa-regular fa-user"></i></li>
                </ul>
            </div>
        </div>
    </div> -->









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
        echo "

        <table>
                <tr>
                    <th class='title'>
                        Title
                    </th>
                    <th class='genres'>
                        Genres</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td class='title'>
                    <div class='card'>
        <div class='poster'>
            <img src='../Assets/Images/Posters/1.jpg'>
        </div>
        <div class='details'>
            <img src='../Assets/Images/Logos/1.png' class='logo'>
            <div class='rating'>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-regular fa-star'></i>
            <span>4/5</span>
            </div>
            <div class='tags'>
                <span>". htmlspecialchars($row["genres"]) ."</span>
            </div>
            <div class='info'>
                <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>
            </div>
            <div class='comment'>
                <h4>Comment</h4>
                <ul>
                    <li>
                        <i class='fa-regular fa-user'></i>
                        <p>Integer nibh mauris, feugiat sagittis eleifend et, faucibus vel neque.</p>
                    </li>
                    <li>
                        <i class='fa-regular fa-user'></i>
                        <p>Phasellus tincidunt congue dignissim.</p>
                    </li>
                    <li><i class='fa-regular fa-user'></i></li>
                </ul>
            </div>
            <i class='fa-regular fa-heart'></i>
        </div>
    </div>
                        <a href='movieDetails.php?movieId=" . $row['movieId'] .
                "'>" . htmlspecialchars($row["title"]) .  " 
                        </a>
                    </td>
                    <td class='genres'>" . htmlspecialchars($row["genres"]) . "
                    </td>
                </tr>";
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
<script>
</script>

</html>