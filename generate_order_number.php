<?php
header('Content-Type: application/json; charset=utf-8');
$pdo = new PDO("mysql:host=localhost;dbname=store_db;charset=utf8", "root", "");

// جلب آخر رقم طلب
$stmt = $pdo->query("SELECT order_number FROM orders ORDER BY id DESC LIMIT 1");
$last = $stmt->fetch(PDO::FETCH_ASSOC);

if ($last && is_numeric($last['order_number'])) {
    $next = str_pad($last['order_number'] + 1, 5, "0", STR_PAD_LEFT);
} else {
    $next = "01000";
}

echo json_encode(["order_number" => $next]);
?>