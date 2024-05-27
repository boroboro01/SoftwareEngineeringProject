<?php
session_start();


echo $_SESSION['user_id'];
echo $_SESSION['year'];

// echo $_SESSION['best_movie_id'];

// 데이터베이스 연결
require_once '../php_files/db.php';

// 세션에서 best_movie_id 가져오기
if (isset($_SESSION['best_movie_id'])) {
    $best_movie_id = $_SESSION['best_movie_id'];

    echo $best_movie_id;

    // SQL 쿼리 작성
    $sql = "SELECT * FROM movies WHERE id = '$best_movie_id'";

    // 쿼리 실행
    $result = $conn->query($sql);

    // 결과 처리
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // 영화 정보 출력
            $movie_title = $row['title'];
            $movie_poster = $row['poster'];
            $movie_rating = $row['rating'];

            // 영화 정보 출력
            echo "<h2 class='panel_title'>$movie_title</h2>";
            echo "<img class='movie_poster' src='../Assets/Images/Posters/$movie_poster' alt='$movie_title'>";
            echo "<div class='rating_star'>";
            echo "<ul>";
            for ($i = 0; $i < 5; $i++) {
                if ($i < $movie_rating) {
                    echo "<li><i class='fas fa-star'></i></li>";
                } else {
                    echo "<li><i class='far fa-star'></i></li>";
                }
            }
            echo "</ul>";
            echo "</div>";
        }
    } else {
        echo "0 results";
    }

    // 연결 종료
    $conn->close();
} else {
    echo $_SESSION['genre'];
    echo $_SESSION['year'];
    echo "No movie selected";
}
?>