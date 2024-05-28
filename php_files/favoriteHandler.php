<?php
session_start();
header('Content-Type: application/json');  // JSON 응답임을 명시

// 로그인 상태 검증
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'db.php'; // 데이터베이스 연결 스크립트

$movieId = $_GET['movieId'];
$userId = $_SESSION['user_id'];
$favorited = $_GET['favorited'] === '1';


// 좋아요 처리 로직
try {
    if ($favorited) {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND movie_id = ?";
    } else {
        $sql = "INSERT INTO favorites (user_id, movie_id) VALUES (?, ?)";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL error: " . $conn->error);
    }
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
