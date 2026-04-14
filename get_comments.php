<?php
require "db.php";

$product_id = $_GET['product_id'];

$result = $conn->query("SELECT * FROM comments WHERE product_id='$product_id' ORDER BY id DESC");

$comments = [];

while($row = $result->fetch_assoc()){
    $comments[] = $row;
}

echo json_encode($comments);