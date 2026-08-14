<<?php

$host = "127.0.0.1";
$port = 3307;
$dbname = "unibus";
$username = "unibus_user";
$password = "unibus123";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
    $port
);

if ($conn->connect_error) {

    die("Database connection failed: " . $conn->connect_error);

}

$conn->set_charset("utf8mb4");

?>