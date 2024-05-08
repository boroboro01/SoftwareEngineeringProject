<?php
session_start();  // 세션 시작이 누락되어 있으면 추가

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 로그인하지 않았다면 로그인 페이지로 리디렉션
    header('Location: login.html');
    exit;  // 리디렉션 후 스크립트 실행 종료
}

require_once 'db.php';  // 데이터베이스 연결

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$movieId = isset($_GET['movieId']) ? $_GET['movieId'] : 0;
$userId = $_SESSION['user_id'];
$favorited = $_GET['favorited'] === '1';

// 입력 검증 (예: movieId가 정수인지 확인)
if (!filter_var($movieId, FILTER_VALIDATE_INT)) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID']);
    exit;
}

if ($favorited) {
    // 좋아요 제거
    $sql = "DELETE FROM favorites WHERE user_id = ? AND movie_id = ?";
} else {
    // 좋아요 추가
    $sql = "INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)";
}

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error']);
    exit;
}

$stmt->bind_param("ii", $userId, $movieId);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database execute error']);
}

$stmt->close();
$conn->close();
?>