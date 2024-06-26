<?php
// 데이터베이스 연결
require_once 'db.php';

$genre = $_GET['genres'];
$year = $_GET['year'];
$third_data = $_GET['third_data'];

$sql = "SELECT * FROM movies WHERE genres = '$genre'";

$result = $conn->query($sql);

$filtered_movie_ids = array(); // 년도 조건을 만족하는 영화 아이디를 저장할 배열

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        preg_match('/\((\d{4})\)/', $row['title'], $matches);
        $movie_year = isset($matches[1]) ? $matches[1] : null;

        // 년도가 조건을 만족하는 경우에만 영화 아이디를 배열에 저장
        if ($movie_year !== null && $movie_year >= $year && $movie_year < ($year + 10)) {
            $filtered_movie_ids[] = $row['movieId'];
        }
    }
} else {
    echo "No movies found with the given genre.";
}

if ($third_data === 'comment') {
    // 가장 많은 태그 수를 가진 영화의 아이디를 저장할 변수
    $best_movie_id = null;
    $max_tag_count = 0;

    // 각 영화 아이디에 대해 태그 수를 계산하여 가장 많은 태그 수를 가진 영화의 아이디 찾기
    foreach ($filtered_movie_ids as $movie_id) {
        // 해당 영화 아이디를 가진 태그의 수를 계산하는 쿼리
        $sql = "SELECT COUNT(*) AS tag_count FROM tags WHERE movieId = $movie_id";
        $result = $conn->query($sql);

        // 쿼리 결과에서 태그 수를 가져옴
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tag_count = $row['tag_count'];

            // 현재 영화의 태그 수가 최대 태그 수보다 크면 업데이트
            if ($tag_count > $max_tag_count) {
                $max_tag_count = $tag_count;
                $best_movie_id = $movie_id;
            }
        }
    }

    // 가장 많은 태그 수를 가진 영화의 아이디 출력 (테스트용)
}
if ($third_data === 'rating') {
    $best_movie_id = null;
    $max_av_rating = 0;

    foreach ($filtered_movie_ids as $movie_id) {
        $sql = "SELECT AVG(r.rating) AS average_rating FROM 
        ratings r WHERE r.movieId = $movie_id";
        $result = $conn->query($sql);

        // 쿼리 결과에서 태그 수를 가져옴
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $av_rating = $row['average_rating'];

            // 현재 영화의 태그 수가 최대 태그 수보다 크면 업데이트
            if ($av_rating > $max_av_rating) {
                $max_av_rating = $av_rating;
                $best_movie_id = $movie_id;
            }
        }
    }
}

// 연결 종료
$conn->close();
if ($best_movie_id !== null) {
    header("Location: ../php_files/recommendPanel.php?best_movie_id=" . $best_movie_id);
    exit;
} else {
    // echo "No movies found with the given criteria. with $best_movie_id";
    header("Location: ../php_files/recommendPanel.php?best_movie_id=" . 1);
}
?>