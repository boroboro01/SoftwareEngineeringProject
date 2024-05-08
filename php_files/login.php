<?php
session_start(); // 세션을 시작합니다.

// 데이터베이스 연결
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movielens";
$conn = new mysqli($servername, $username, "", $dbname);

// 연결 에러 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 폼에서 제출된 사용자 이름과 비밀번호
$username = $_POST['username'];
$password = $_POST['password'];

// 사용자 검증을 위한 SQL 쿼리
$sql = "SELECT id, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

// 사용자가 있는지 확인
if ($stmt->num_rows == 1) {
    // 결과 바인딩
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    // 비밀번호 검증
    if (password_verify($password, $hashedPassword)) {
        // 로그인 성공, 세션 변수 설정
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $id; // 사용자 ID를 세션에 저장
        $_SESSION['username'] = $username; // 사용자 이름을 세션에 저장

        // 메인 페이지로 리다이렉션
        header('Location: main.php');
        exit;
    } else {
        // 비밀번호가 일치하지 않는 경우
        echo "Invalid password";
    }
} else {
    // 사용자가 존재하지 않는 경우
    echo "Invalid username";
}

$stmt->close();
$conn->close();
?>