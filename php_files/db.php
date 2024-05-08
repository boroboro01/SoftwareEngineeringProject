<?php
// 데이터베이스 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "movielens";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, "", $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>