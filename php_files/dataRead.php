<?php
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "movielens";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, "", $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CSV 파일 열기
$handle = fopen("../data_files/movies.csv", "r");
if ($handle !== FALSE) {
    // 첫 번째 라인 (헤더) 읽기 및 무시
    fgetcsv($handle, 1000, ",");

    // 라인별로 읽기
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $movieId = $data[0];
        $title = addslashes($data[1]); // SQL 쿼리를 위해 escape 처리
        $genres = addslashes($data[2]);

        // 데이터베이스에 데이터 삽입
        $sql = "INSERT INTO movies (movieId, title, genres) VALUES ('$movieId', '$title', '$genres')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    fclose($handle);
}

$handle = fopen("../data_files/dataratings.csv", "r");
if ($handle !== FALSE) {
    fgetcsv($handle);  // 헤더 스킵
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $userId = $data[0];
        $movieId = $data[1];
        $rating = $data[2];
        $timestamp = $data[3];
        $sql = "INSERT INTO ratings (userId, movieId, rating, timestamp) VALUES ('$userId', '$movieId', '$rating', '$timestamp')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    fclose($handle);
}

$handle = fopen("../data_files/tags.csv", "r");
if ($handle !== FALSE) {
    fgetcsv($handle);  // 헤더 스킵
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $userId = $data[0];
        $movieId = $data[1];
        $tag = addslashes($data[2]);
        $timestamp = $data[3];
        $sql = "INSERT INTO tags (userId, movieId, tag, timestamp) VALUES ('$userId', '$movieId', '$tag', '$timestamp')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    fclose($handle);
}
$conn->close();



// 데이터베이스 연결 닫기
$conn->close();
?>
