<?php
header("Content-Type: application/json; charset=utf-8");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);
$newStatus = $conn->real_escape_string($data['status']);

$sql = "UPDATE orders SET status='$newStatus' WHERE id=$id";
if($conn->query($sql)){
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>$conn->error]);
}
?>