<?php
session_start();
// 데이터베이스 연결
require_once 'db.php';

// 추천된 장르 및 년도 받기
$genre = $_POST['genre']; // 추천된 장르
$year = $_POST['year']; // 추천된 년도

// SQL 쿼리 작성
$sql = "SELECT * FROM movies WHERE genre = '$genre' AND year = '$year'";

// 쿼리 실행
$result = $conn->query($sql);

// 결과 처리
if ($result->num_rows > 0) {
    // 결과를 배열로 변환하여 반환
    $movies = array();
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }

    // 세 번째 데이터 확인 (레이팅 또는 코멘트)
    $third_data = $_POST['third_data'];

    if ($third_data === 'rating') {
        // 레이팅일 경우
        $max_rating = 0;
        $best_movie_id = null;
        foreach ($movies as $movie) {
            $rating_sum += $movie['rating'];
            $rating_count++;
        }
        $average_rating = $rating_sum / $rating_count;

        // 가장 높은 평균 별점을 가진 영화 찾기
        foreach ($movies as $movie) {
            if ($movie['rating'] > $max_rating) {
                $max_rating = $movie['rating'];
                $best_movie_id = $movie['id'];
            }
        }
        echo $best_movie_id;
        $_SESSION['best_movie_id'] = $best_movie_id;
    } elseif ($third_data === 'comment') {
        // 코멘트일 경우
        $max_tags = 0;
        $most_tagged_movie_id = null;
        foreach ($movies as $movie) {
            $tag_count = count(explode(',', $movie['tags']));
            if ($tag_count > $max_tags) {
                $max_tags = $tag_count;
                $most_tagged_movie_id = $movie['id'];
            }
        }
        echo $most_tagged_movie_id;
        $_SESSION['best_movie_id'] = $most_tagged_movie_id;
    } else {
        echo "Invalid third data type";
    }
} else {
    echo "0 results";
}


// 연결 종료
$conn->close();
?>