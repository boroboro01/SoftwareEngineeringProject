<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 폼 데이터 받기
$username = $_POST['username'];
$plainPassword = $_POST['password'];
$email = $_POST['email'];

// 비밀번호 해싱
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// 사용자 정보 데이터베이스에 저장
$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (false === $stmt) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("sss", $username, $hashedPassword, $email);
$success = $stmt->execute();

if ($success) {
    echo "<script>alert('Registration successful!');
    window.location.href = '../html_files/login.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>