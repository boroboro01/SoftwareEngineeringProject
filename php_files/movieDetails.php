<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../css_files/main_style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dongle&family=M+PLUS+Rounded+1c&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap');
        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
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

        .infobox {
            margin-top: 20px;
            text-align: center;
        }

        .poster img {
            max-width: 300px;
            border-radius: 10px;
            margin: 0 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 25);
        }

        .Taging {
            display: flex;
        }

        .btnbox {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btnbox a {
            display: inline-block;
            padding: 10px 20px;
            background-color: white;
            color: black;
            text-decoration: none;
            border-radius: 20px;
        }
        .favorite-toggle {
            display: inline-block;
            margin-top: 20px;
        }
        .heart {
            font-size: 24px;
            color: red;
            text-decoration: none;
        }
        .content {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* 영화 설명 박스 */
        .description {
            font-size: 1.2em;
            line-height: 2em;
            text-align: left;
            width: 30vw;
            height: 50vh;
            background-color: #0d1423;
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            margin: 0 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 25);
            overflow: scroll;
        }

        /* 팝업 스타일 */
        .popup {
            display: none;
            position: fixed;
            top: 40%;
            left: 25%;
            transform: translate(-50%, -50%);
            width: 30vw;
            height: 50vh;
            background-color: #0d1423;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: scroll;
        }

        .popup input,
        .popup textarea {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        .popup button {
    display: inline-block;
    justify-content: center;
    width: 80px;
    padding: 10px;
    background-color: #fff;
    border-radius: 50px;
    margin-top: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 1em;
    transition: 300ms ease;
}
.popup button.close{
    display: inline-block;
    color: #fff;
    justify-content: center;
    width: 80px;
    padding: 10px;
    background-color: #010101;
    border-radius: 50px;
    margin-top: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 1em;
    transition: 300ms ease;
}
        /* .popup button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: white;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button.close {
            background-color: red;
            color: white;
        } */
        .comment-box {
            background-color: #0d1423;
            border-radius: 10px;
            margin-left: 5px;
            height: 50vh;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: start;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 25);
            overflow: scroll;
        }
        .comment {
            margin-top: 15px;
        }
        .title {
            display: inline-block;
            font-size: 2em;
        }
        .comment p{
            display: inline-block;
            font-size: 1em;
        }
        .comment i{
            margin-right: 10px;
            font-size: 1.2em;
            padding: 5px;
            border: 2px solid #fff;
            border-radius: 50%;
        }
        .popup-title {
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        .favorite-toggle {
            text-decoration: none;
        }
        .favorite-toggle i {
            font-size: 2em;
            color: #f52c4a;
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
            die("<p>SQL error: " . $conn->error . "</p>");
        }
        echo '<nav>
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
        </div>
    </nav>';
    echo'<div class="wrapper">';
        if ($movie = $result->fetch_assoc()) {
            echo "<div class='subtitle'><p>" . htmlspecialchars($movie['title']) . "</p></div>";
            echo "<div class=\"infobox\"><p>Average Rating: " . round($movie['average_rating'], 2) . "</p>";
        } else {
            echo "<header><p>No movie found.</p></header>";
        }

        $sql = "SELECT tag FROM tags WHERE movieId = $movieId";
        $tagsResult = $conn->query($sql);
        if (!$tagsResult) {
            die("<p>SQL error: " . $conn->error . "</p>");
        }
        echo"<div class = 'content'>";
        echo "<div class='poster'>
            <img src='../Assets/Images/Posters/" . $movieId . ".jpg' alt='" .
            htmlspecialchars($movieId) . " Poster'>
            </div>";
            $sql = "SELECT description FROM movie_descriptions WHERE movie_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $stmt->bind_result($description);
        $stmt->fetch();
        $stmt->close();
        echo'
        <div class="description">';
            echo htmlspecialchars($description);
            echo'
        </div>';
        echo "<p class=\"Taging\"> ";
        echo '<div class="comment-box">';
        echo '<span class="title">Comments</span>';
        while ($tag = $tagsResult->fetch_assoc()) {
            echo '<div class="comment"> <i class="fa-regular fa-user"></i>';
            echo '<p>';
            echo htmlspecialchars($tag['tag']) . " ";
            echo '</p>';
            echo '</div>';
        }
        echo '</div>';
        
        echo "</p></div>";
        echo "<div class=\"btnbox\"><a href='#' id='addCommentButton'>Add rating and comment</a>";
        echo "<a href='movieList.php'>Back to Movie List</a></div>";

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
        ' . ($favorited ? '<i class="fa-solid fa-heart"></i>' : '<i class="fa-regular fa-heart"></i>') . '
        </a>';
         echo'
        </div></div>';
    } else {
        echo "<p>Movie not found.</p>";
    }

    $conn->close();
    ?>
    <div class="popup" id="popupForm">
        <div class="popup-title">How about this movie?</div>
        <form action="addComment.php" method="POST">
            <input type="hidden" name="movieId" value="<?php echo $movieId; ?>">
            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required></textarea>
            <button type="submit">Submit</button>
            <button type="button" class="close">Close</button>
        </form>
</div>
    <script>
        document.getElementById('addCommentButton').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('popupForm').style.display = 'block';
        });

        document.querySelector('.popup .close').addEventListener('click', function () {
            document.getElementById('popupForm').style.display = 'none';
        });

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
    </script>
</body>

</html>