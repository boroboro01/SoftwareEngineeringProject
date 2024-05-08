<?php

session_start(); // 세션 시작

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 로그인 상태가 아닌 경우 경고 메시지를 출력하고 로그인 페이지로 리디렉션
    echo "<script>alert('You must be logged in to add tag and rating');
    window.location.href = '../html_files/login.html';</script>";
    exit; // 추가 실행을 방지
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
$userId = $_SESSION['user_id']; // 세션에서 사용자 ID 가져오기

// 별점 데이터 저장
$sql = "INSERT INTO ratings (userId, movieid, rating) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iid", $userId, $movieId, $rating);
$stmt->execute();

// 태그 데이터 저장
$sql = "INSERT INTO tags (userId, movieId, tag) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $userId, $movieId, $tag);
$stmt->execute();

$stmt->close();
$conn->close();

echo "<script>alert('Your comment has been saved.');
    window.location.href = './movieList.php';</script>";
?>