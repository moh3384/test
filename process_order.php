






<?php
header("Content-Type: application/json; charset=utf-8");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "بيانات مفقودة"]);
    exit;
}

$name = $conn->real_escape_string($data['name']);
$phone = $conn->real_escape_string($data['phone']);
$email = $conn->real_escape_string($data['email']);
$address = $conn->real_escape_string($data['address']);
$total_quantity = intval($data['total_quantity']);
$total_price = floatval($data['total_price']);
$order_details = $conn->real_escape_string($data['order_details']);
$payment_method = $conn->real_escape_string($data['payment_method']);
$order_number = $conn->real_escape_string($data['order_number']);

// 🧾 إدخال الطلب في القاعدة
$sql = "INSERT INTO orders 
(order_number, customer_name, customer_phone, customer_email, customer_address, total_quantity, total_price, order_details, payment_method)
VALUES 
('$order_number', '$name', '$phone', '$email', '$address', '$total_quantity', '$total_price', '$order_details', '$payment_method')";

if ($conn->query($sql)) {
    $storePhone = "966501871284"; // ضع رقم المتجر هنا
    $message = urlencode($data['whatsapp_message']);
    $url = "https://wa.me/$storePhone?text=$message";

    echo json_encode([
        "status" => "success",
        "url" => $url
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "خطأ في حفظ الطلب: " . $conn->error
    ]);
}
?>