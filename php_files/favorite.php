<?php
session_start();

// 로그인 상태 검증
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You must be logged in to view favorites');
    window.location.href = 'login.html';</script>";
    exit;
}

require_once 'db.php'; // 데이터베이스 연결 스크립트

$userId = $_SESSION['user_id'];

// 좋아요 누른 영화 목록 조회
$sql = "SELECT m.movieId, m.title, m.genres FROM movies m JOIN favorites f ON m.movieId = f.movie_id WHERE f.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Favorite Movies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1>Your Favorite Movies</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Genres</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['genres']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>You have no favorite movies yet.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>