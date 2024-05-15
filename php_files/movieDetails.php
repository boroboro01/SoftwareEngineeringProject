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

        $userId = $_SESSION['user_id'];  // 사용자 ID를 세션에서 가져옵니다.
        $favorited = 0;  // 기본적으로 좋아요가 되어있지 않다고 가정
    
        // 좋아요 상태를 확인하는 쿼리
        $favQuery = "SELECT 1 FROM favorites WHERE user_id = ? AND movie_id = ?";
        $favStmt = $conn->prepare($favQuery);
        if ($favStmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }
        $favStmt->bind_param("ii", $userId, $movieId);
        $favStmt->execute();
        $favResult = $favStmt->get_result();
        if ($favResult->fetch_assoc()) {
            $favorited = 1;  // 좋아요 상태가 확인되면 변수를 1로 설정
        }
        $favStmt->close();
        echo '<a href="#" class="favorite-toggle" data-movie-id="' . $movieId . '" data-favorited="' . $favorited . '">
        <span class="heart">' . ($favorited ? '&#x2665;' : '&#x2661;') . '</span></a>';
    } else {
        echo "<p>Movie not found.</p>";
    }

    $conn->close();
    ?>
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
                                    this.innerHTML = '<span class="heart">&#x2661;</span>'; // 빈 하트
                                    this.setAttribute('data-favorited', '0');
                                    alert('Favorite removed'); // 추가: 제거 성공 메시지
                                } else {
                                    this.innerHTML = '<span class="heart">&#x2665;</span>'; // 꽉 찬 하트
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
    </script>
</body>

</html>