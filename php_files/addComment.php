<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "movielens";

$conn = new mysqli($servername, $username, "", $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movieId = intval($_POST['movieId']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $userId = $_SESSION['user_id'];

    // 별점과 코멘트를 데이터베이스에 저장
    $sql = "INSERT INTO ratings (userId, movieId, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("iiis", $userId, $movieId, $rating, $comment);
    if ($stmt->execute()) {
        // 성공 메시지를 추가하고 영화 상세 페이지로 리다이렉트
        $_SESSION['message'] = "Comment added successfully!";
        header("Location: movieDetails.php?movieId=" . $movieId);
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

$conn->close();
?>