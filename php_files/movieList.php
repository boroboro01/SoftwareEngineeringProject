<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css_files/movie_card.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dongle&family=M+PLUS+Rounded+1c&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap');

        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container {
            display: flex;
            width: 80vw;
            padding: 20px;
            overflow-x: auto;
        }

        .container::-webkit-scrollbar {
            display: none;
        }

        th,
        td {
            padding: 20px;
            text-align: left;
        }

        .pagination {
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

        @keyframes imagerotation {}
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
        <div class='wrapper'>
        <div class='container'>
            <table>
                <tr>";
        while ($row = $result->fetch_assoc()) {
            echo "
                    <td class='title'>
    <div class='card'>
        <div class='poster'>
            <img src='../Assets/Images/Posters/" . $row['movieId'] . ".jpg' alt='" .
                htmlspecialchars($row['title']) . " Poster'>
        </div>
        <div class='details'>
            <img src='../Assets/Images/Logos/1.png' class='logo'>
            <div class='rating'>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-solid fa-star'></i>
            <i class='fa-regular fa-star'></i>
            <span></span>
            </div>
            <div class='tags'>
                <span>" . htmlspecialchars($row["genres"]) . "</span>
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
                    <li>
                        <i class='fa-solid fa-pen'></i>
                        <form>
                            <input type='text' placeholder='type your comment'/>
                        </form>
                    </li>
                </ul>
            </div>
            ";
            echo "
            
        </div>
    </div>
                        <a href='movieDetails.php?movieId=" . $row['movieId'] .
                "'>" . htmlspecialchars($row["title"]) . " 
                        </a>
                        
                    </td>";

        }
        echo "
                </tr>
            </table>
        </div>
        ";
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
    echo "</div></wrapper>";

    $conn->close();
    ?>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var favoriteLinks = document.querySelectorAll('.favorite-toggle');

        favoriteLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var movieId = this.getAttribute('data-movie-id');
                var favorited = this.getAttribute('data-favorited')
                var isFavorited = favorited === '1';

                fetch('favoriteHandler.php?movieId=' + movieId + '&favorited=' + favorited)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // 좋아요 상태에 따라 아이콘 변경
                            if (isFavorited) {
                                this.innerHTML = '<i class="fa-regular fa-heart"></i>'; // 빈 하트
                                this.setAttribute('data-favorited', '0');
                                alert('Favorite removed'); // 추가: 제거 성공 메시지
                            } else {
                                this.innerHTML = '<i class="fa-solid fa-heart"></i>'; // 꽉 찬 하트
                                this.setAttribute('data-favorited', '1');
                                alert('Favorite added'); // 추가: 추가 성공 메시지
                            }
                        } else {
                            alert('Error toggling favorite: ' + data.message); // 실패 메시지
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error.message);
                    });
            });
        });
    });

    //scroll horizontal
    const scrollContainer = document.querySelector(".container");

    scrollContainer.addEventListener("wheel", (evt) => {
        evt.preventDefault();
        scrollContainer.scrollLeft += evt.deltaY;
    });

</script>

</html>