<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];

require_once 'db.php'; // 데이터베이스 연결 스크립트

// 사용자가 좋아요 누른 영화 중 가장 많이 나온 장르의 영화
$sql = "SELECT m.genres, COUNT(*) AS genre_count
        FROM favorites f
        JOIN movies m ON f.movie_id = m.movieId
        WHERE f.user_id = ?
        GROUP BY m.genres
        ORDER BY genre_count DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $mostPopularGenre = $row['genres'];

    // 가장 많이 나온 장르의 영화 중 별점을 단 수가 많고 평균 별점이 높은 영화
    $sql = "SELECT m.movieId, m.title, AVG(r.rating) AS average_rating
            FROM favorites f
            JOIN movies m ON f.movie_id = m.movieId
            LEFT JOIN ratings r ON m.movieId = r.movieId
            WHERE f.user_id = ? AND m.genres = ?
            GROUP BY m.movieId, m.title
            HAVING COUNT(r.rating) > 10
            ORDER BY average_rating DESC
            LIMIT 3";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $mostPopularGenre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recommendedMovies = [];
        while ($row = $result->fetch_assoc()) {
            $recommendedMovies[] = [
                'movieId' => $row['movieId'],
                'title' => $row['title'],
                'average_rating' => round($row['average_rating'], 2)
            ];
        }
        // 추천된 영화 정보를 JSON 파일로 저장
        file_put_contents('recommended_movies.json', json_encode($recommendedMovies));
        echo "Recommended movies saved successfully.";
    } else {
        echo "No recommended movies found.";
    }
} else {
    echo "No favorite movies found.";
}

$stmt->close();
$conn->close();
?>