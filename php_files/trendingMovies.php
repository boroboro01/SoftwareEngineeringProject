<?php
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "movielens";
$conn = new mysqli($servername, $username, "", $dbname);

header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$sql = "SELECT m.movieId, m.title, COALESCE(AVG(r.rating), 0) AS average_rating, COUNT(r.rating) AS rating_count
        FROM movies m
        LEFT JOIN ratings r ON m.movieId = r.movieId
        GROUP BY m.movieId, m.title
        ORDER BY rating_count DESC, average_rating DESC
        LIMIT 3";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = [
            'movieId' => $row['movieId'],
            'title' => $row['title'],
            'average_rating' => round($row['average_rating'], 2),
            'rating_count' => $row['rating_count']
        ];
    }
    echo json_encode(['success' => true, 'movies' => $movies]);
} else {
    echo json_encode(['success' => false, 'message' => 'No trending movies found']);
}

$conn->close();
?>