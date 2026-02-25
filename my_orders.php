<?php
require "db.php";
session_start();

// التحقق من تسجيل الدخول
if(!isset($_SESSION['user_phone'])){
  die("<div style='text-align:center;margin-top:50px;font-family:Segoe UI,Tahoma;'>
        <h2>⚠️ يجب تسجيل الدخول لعرض الطلبات</h2>
        <a href='login.php' style='background:#7c39c9;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;'>تسجيل الدخول</a>
      </div>");
}

$phone = $conn->real_escape_string($_SESSION['user_phone']);
$result = $conn->query("SELECT order_number, total_quantity, total_price, payment_method, created_at, status FROM orders WHERE customer_phone='$phone' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>طلباتي | الأسطورة سبورت</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
:root{
  --main:#7c39c9;
  --accent:#9d5ce8;
  --bg:#f8f9fa;
  --text:#333;
}
body {
  font-family:'Segoe UI', Tahoma;
  background:var(--bg);
  color:var(--text);
  margin:0;
  padding:0;
  direction:rtl;
}
header {
  background:linear-gradient(90deg,var(--main),var(--accent));
  color:#fff;
  padding:15px 25px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}
header h2 {
  margin:0;
  font-size:22px;
}
header a {
  color:#fff;
  text-decoration:none;
  font-weight:bold;
  border:1px solid rgba(255,255,255,0.4);
  padding:6px 12px;
  border-radius:8px;
  transition:0.3s;
}
header a:hover {
  background:rgba(255,255,255,0.2);
}
.container {
  max-width:700px;
  margin:40px auto;
  padding:20px;
}
.order {
  background:#fff;
  border-radius:12px;
  padding:18px;
  margin-bottom:15px;
  box-shadow:0 2px 5px rgba(0,0,0,0.1);
  transition:0.3s;
  border-right:5px solid var(--main);
}
.order:hover {
  transform:translateY(-3px);
}
.order p {
  margin:6px 0;
}
.status {
  font-weight:bold;
  border-radius:8px;
  padding:6px 12px;
  color:#fff;
  display:inline-block;
}
.status.pending { background:#ff9800; }     /* قيد التنفيذ */
.status.ready { background:#007bff; }      /* جاهز للشحن */
.status.done { background:#28a745; }       /* تم التوصيل */
.empty {
  text-align:center;
  color:#777;
  font-size:18px;
  margin-top:50px;
}
</style>
</head>
<body>

<header>
  <h2><i class="fa-solid fa-box"></i> طلباتي</h2>
  <a href="index.html"><i class="fa fa-home"></i> الرئيسية</a>
</header>

<div class="container">
<?php
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    // 🟣 تحويل الحالة إلى نص واضح للعميل
    if($row['status'] === 'غير مدفوع') {
      $statusText = "قيد التنفيذ";
      $statusClass = "pending";
    } elseif($row['status'] === 'مدفوع') {
      $statusText = "جاهز للشحن";
      $statusClass = "ready";
    } else {
      $statusText = "تم التوصيل";
      $statusClass = "done";
    }

    echo "
    <div class='order'>
      <p>🆔 <strong>رقم الطلب:</strong> {$row['order_number']}</p>
      <p>🔢 <strong>عدد القطع:</strong> {$row['total_quantity']}</p>
      <p>💳 <strong>طريقة الدفع:</strong> {$row['payment_method']}</p>
      <p>💵 <strong>الإجمالي:</strong> {$row['total_price']} ريال</p>
      <p>🕒 <strong>تاريخ الطلب:</strong> {$row['created_at']}</p>
      <p>📦 <strong>الحالة:</strong> <span class='status {$statusClass}'>{$statusText}</span></p>
    </div>";
  }
} else {
  echo "<div class='empty'>🚫 لا توجد طلبات حتى الآن</div>";
}
?>
</div>



</body>
</html>