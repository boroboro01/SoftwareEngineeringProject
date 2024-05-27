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

    // 별점 데이터 저장 또는 업데이트
    $sql = "INSERT INTO ratings (userId, movieId, rating) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $userId, $movieId, $rating);
    if (!$stmt->execute()) {
        echo "<script>alert('Failed to save or update your rating.'); window.location.href = './movieList.php';</script>";
        exit;
    }

    // 태그 데이터 저장 또는 업데이트
    $sql = "INSERT INTO tags (userId, movieId, tag) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE tag = VALUES(tag)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $movieId, $comment);
    if (!$stmt->execute()) {
        echo "<script>alert('Failed to save or update your tag.'); window.location.href = './movieList.php';</script>";
        exit;
    }

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