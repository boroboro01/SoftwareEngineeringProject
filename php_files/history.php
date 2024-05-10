<?php
session_start();

// 로그인 상태 검증
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You must be logged in to view your history');
    window.location.href = '../html_files/login.html';</script>";
    exit;
}

require_once 'db.php'; // 데이터베이스 연결 스크립트

$userId = $_SESSION['user_id'];

// 사용자가 평가한 영화 목록 조회
$sql = "SELECT m.movieId, m.title, m.genres, r.rating, t.tag 
FROM movies m 
JOIN ratings r ON m.movieId = r.movieId AND r.userId = ?
JOIN tags t ON m.movieId = t.movieId AND t.userId = ?
GROUP BY m.movieId
ORDER BY m.title ASC;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Movie History</title>
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
    <h1>Your Movie History</h1>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Title</th><th>Year</th><th>Rating</th><th>Tag</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['genres']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tag']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>You have not rated or tagged any movies yet.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>