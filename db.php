<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "store_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات");
}

$conn->set_charset("utf8mb4");
?>