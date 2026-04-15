<?php
require "db.php";
session_start();

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    if (!preg_match('/^05\d{8}$/', $phone)) {
        $error = "⚠️ رقم الهاتف غير صالح. يجب أن يبدأ بـ05 ويتكون من 10 أرقام.";
    } else {
   $check = $conn->prepare("SELECT customer_name FROM orders WHERE customer_phone = ? LIMIT 1");
$check->bind_param("s", $phone);
$check->execute();
$result = $check->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['user_phone'] = $phone;
    $_SESSION['user_name'] = $row['customer_name']; // 🔥 الاسم هنا
    header("Location: my_orders.php");
    exit();

} else {
    $error = " لا يوجد حساب بنفس الرقم ، لإنشاء حساب يجب إتمام طلب واحد على الأقل.";
}
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تسجيل الدخول | الأسطورة سبورت</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<style>
:root {
  --main:#7c39c9;
  --accent:#9d5ce8;
  --bg:#f4f6f9;
  --danger:#e63946;
}
body {
  font-family:'Segoe UI', Tahoma;
  background:var(--bg);
  margin:0;
  padding:0;
  direction:rtl;
}

/* 🔹 زر تسجيل الخروج */
.logout-btn {
  
  top: 20px;
  left: 20px;
  background: var(--danger);
  color: #fff;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
  z-index: 1000;
}
.logout-btn:hover {
  background: #b41623;
  transform: scale(1.05);
}

/* 🔹 صندوق تسجيل الدخول */
.login-container {
  max-width: 380px;
  height: 300px; /* مهم */
  margin: 100px auto;
  background: #fff;
  padding: 20px;
  border-radius: 14px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  
  display: flex;
  flex-direction: column;
  justify-content: center; /* توسيط عمودي */
  align-items: center;     /* توسيط أفقي */
  text-align: center;
}
h2 {
  color: var(--main);
  margin-bottom: 25px;
  font-size: 24px;
  font-weight: bold;
}
input {
  width: 100%;
  padding: 12px;
  margin-bottom: 18px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
  text-align: center;
  transition: 0.3s;
  box-sizing: border-box;
}
input:focus {
  border-color: var(--main);
  box-shadow: 0 0 5px rgba(124,57,201,0.4);
}
button {
  width: 100%;
  background: linear-gradient(90deg, var(--main), var(--accent));
  color: #fff;
  padding: 12px;
  font-size: 17px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}
button:hover {
  transform: scale(1.04);
  box-shadow: 0 2px 10px rgba(124,57,201,0.3);
}
.error {
  color: var(--danger);
  background: #ffe6e6;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 12px;
  font-weight: bold;
  font-size: 14px;
}
a {
  display: block;
  margin-top: 15px;
  color: var(--main);
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}
a:hover {
  color: var(--accent);
}

.top-buttons {
  position: fixed;
  top: 20px;
  left: 20px;
  display: flex;
  gap: 10px;
  z-index: 1000;
}

.home-btn {
  background: linear-gradient(90deg, var(--main), var(--accent));
  color: #fff;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
  
}

.home-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 2px 10px rgba(124,57,201,0.3);
}

/* حركه لطيفه */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<!-- 🔴 زر تسجيل الخروج -->
<div class="top-buttons">
  <a href="index.html" class="home-btn">🔙 الرئيسية</a>
  <a href="login.php?logout=1" class="logout-btn"> تسجيل الخروج</a>
</div>

<div class="login-container">
  <h2><i class="fa-solid fa-user"></i> تسجيل الدخول</h2>

  <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="POST">
    <input type="text" name="phone" placeholder="أدخل رقم الهاتف (مثال: 05XXXXXXXX)" maxlength="10" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
    <button type="submit">تسجيل الدخول</button>
  </form>

 
</div>

</body>
</html>
