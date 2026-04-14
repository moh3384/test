<?php
require "db.php";
session_start();

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$comment = $data['comment'];
$rating = $data['rating'];
$user = $_SESSION['user_phone'];

$stmt = $conn->prepare("UPDATE comments SET comment=?, rating=? WHERE id=? AND user_phone=?");
$stmt->bind_param("siis", $comment, $rating, $id, $user);
$stmt->execute();

echo json_encode(["status"=>"updated"]);