<?php
require "db.php";
session_start();

$id = $_GET['id'];
$user = $_SESSION['user_phone'];

$conn->query("DELETE FROM comments WHERE id='$id' AND user_phone='$user'");

echo json_encode(["status"=>"deleted"]);