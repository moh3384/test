<?php
require "db.php";
session_start();

if (!isset($_SESSION['user_name'])) {
    echo json_encode(["status"=>"error","msg"=>"يجب تسجيل الدخول"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$comment = $data['comment'];
$rating = $data['rating'];
$product_id = $data['product_id'];
$user_phone = $_SESSION['user_phone'];
$user_name = $_SESSION['user_name'];

$stmt = $conn->prepare("INSERT INTO comments (user_phone, user_name, product_id, comment, rating) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $user_phone, $user_name, $product_id, $comment, $rating);
$stmt->execute();

echo json_encode(["status"=>"success"]);