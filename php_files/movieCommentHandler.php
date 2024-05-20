<?php
session_start(); // 세션 시작

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('You must be logged in to add tag and rating');
    window.location.href = '../html_files/login.html';</script>";
    exit;
}

// 데이터베이스 연결 설정
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "movielens";
$conn = new mysqli($servername, $username, "", $dbname);

// 연결 에러 처리
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 폼 데이터 받기
$movieId = $_POST['movieId'];
$rating = $_POST['rating'];
$tag = $_POST['tag'];
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
$stmt->bind_param("iis", $userId, $movieId, $tag);
if (!$stmt->execute()) {
    echo "<script>alert('Failed to save or update your tag.'); window.location.href = './movieList.php';</script>";
    exit;
}

$stmt->close();
$conn->close();

echo "<script>alert('Your comment has been successfully saved or updated.'); window.location.href = './movieList.php';</script>";
?>