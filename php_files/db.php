<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "movielens";

$conn = new mysqli($servername, $username, "", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>