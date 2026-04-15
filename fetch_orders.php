<?php
header("Content-Type: application/json; charset=utf-8");
require "db.php";

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$from = isset($_GET['from']) ? $conn->real_escape_string($_GET['from']) : "";
$to = isset($_GET['to']) ? $conn->real_escape_string($_GET['to']) : "";
$payment = isset($_GET['payment']) ? $conn->real_escape_string($_GET['payment']) : "";

$sql = "SELECT id, order_number, customer_name, customer_phone, customer_email, total_quantity, payment_method, created_at, status 
        FROM orders WHERE 1=1";

if($search){
    $sql .= " AND (customer_phone LIKE '%$search%' 
                  OR customer_email LIKE '%$search%' 
                  OR order_number LIKE '%$search%')";
}
if($from){
    $sql .= " AND DATE(created_at) >= '$from'";
}
if($to){
    $sql .= " AND DATE(created_at) <= '$to'";
}
if($payment){
    $sql .= " AND payment_method = '$payment'";
}

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);
$orders = [];
while($row=$result->fetch_assoc()){
    $orders[]=$row;
}

echo json_encode($orders);
?>
