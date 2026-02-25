<?php
require "db.php"; // ملف الاتصال بالقاعدة
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | إدارة الطلبات</title>
<style>
body { font-family:'Segoe UI', Tahoma; background:#f4f6f9; margin:0; padding:20px; }
h2 { text-align:center; margin-bottom:20px; }
.container { display:flex; gap:20px; flex-wrap:wrap; }
.column { flex:1; min-width:300px; background:#fff; border-radius:12px; padding:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
.column h3 { text-align:center; margin-top:0; }
.order { border:1px solid #ccc; border-radius:8px; padding:10px; margin-bottom:10px; background:#f9f9f9; transition:0.3s; }
.order.fadeOut { opacity:0; transform:translateY(-20px); }
button { padding:5px 10px; border:none; border-radius:6px; cursor:pointer; }
.pay-btn { background:#0f62fe; color:#fff; }
.deliver-btn { background:#28a745; color:#fff; }
.search-box { margin-bottom:20px; }
input[type=text]{ flex:1; padding:8px; border-radius:8px; border:1px solid #ccc; width:100%; }
button.search-btn{ background:#457b9d; color:#fff; margin-left:5px; }
.count { font-weight:bold; color:#333; margin-bottom:10px; display:block; text-align:center; }
.filter-box { display:flex; gap:10px; margin-bottom:10px; flex-wrap:wrap; align-items:center; }
.filter-box label { margin-right:5px; }
</style>
</head>
<body>

<h2>📊 لوحة إدارة الطلبات</h2>

<!-- فلتر التاريخ وطريقة الدفع وزر إعادة تعيين -->
<div class="filter-box">
    <label>من تاريخ:</label>
    <input type="date" id="fromDate">
    <label>إلى تاريخ:</label>
    <input type="date" id="toDate">

    <label>طريقة الدفع:</label>
    <select id="paymentFilter">
        <option value="">-- الكل --</option>
        <option value="الدفع عند الاستلام">الدفع عند الاستلام</option>
        <option value="تحويل بنكي">تحويل بنكي</option>
        <option value="تابي">تابي</option>
        <option value="تمارا">تمارا</option>
    </select>

    <button onclick="loadOrders()" class="search-btn">فلتر</button>
    <button onclick="resetFilters()" class="search-btn" style="background:#e63946;">إعادة تعيين</button>
</div>

<!-- البحث المباشر -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="بحث بالهاتف أو البريد أو رقم الطلب">
</div>

<div class="container">
    <div class="column" id="pendingColumn">
        <h3>الطلبات الغير مدفوعة <span class="count" id="pendingCount">0</span></h3>
        <div id="pendingOrders"></div>
    </div>

    <div class="column" id="paidColumn">
        <h3>الطلبات المدفوعة <span class="count" id="paidCount">0</span></h3>
        <div id="paidOrders"></div>
    </div>

    <div class="column" id="deliveredColumn">
        <h3>تم التوصيل <span class="count" id="deliveredCount">0</span></h3>
        <div id="deliveredOrders"></div>
    </div>
</div>

<script>
// عناصر البحث والفلاتر
const searchInput = document.getElementById("searchInput");
const fromDate = document.getElementById("fromDate");
const toDate = document.getElementById("toDate");
const paymentFilter = document.getElementById("paymentFilter");

// دالة إعادة تعيين الفلاتر
function resetFilters(){
    fromDate.value = "";
    toDate.value = "";
    paymentFilter.value = "";
    searchInput.value = "";
    loadOrders();
}

// البحث مباشر عند الكتابة
searchInput.addEventListener("input", ()=>loadOrders());
fromDate.addEventListener("change", loadOrders);
toDate.addEventListener("change", loadOrders);
paymentFilter.addEventListener("change", loadOrders);

// جلب وعرض الطلبات
async function loadOrders(){
    const search = searchInput.value.trim();
    const from = fromDate.value;
    const to = toDate.value;
    const payment = paymentFilter.value;

    let url = 'fetch_orders.php?';
    if(search) url += 'search=' + encodeURIComponent(search) + '&';
    if(from) url += 'from=' + encodeURIComponent(from) + '&';
    if(to) url += 'to=' + encodeURIComponent(to) + '&';
    if(payment) url += 'payment=' + encodeURIComponent(payment) + '&';

    const res = await fetch(url);
    const data = await res.json();

    const pendingContainer = document.getElementById('pendingOrders');
    const paidContainer = document.getElementById('paidOrders');
    const deliveredContainer = document.getElementById('deliveredOrders');

    pendingContainer.innerHTML = paidContainer.innerHTML = deliveredContainer.innerHTML = '';

    let pendingCount=0, paidCount=0, deliveredCount=0;

    data.forEach(o=>{
        const div = document.createElement('div');
        div.classList.add('order');
        div.dataset.id = o.id;
        div.innerHTML = `
            <p>👤 ${o.customer_name}</p>
            <p>📞 ${o.customer_phone}</p>
            <p>✉️ ${o.customer_email}</p>
            <p>🆔 رقم الطلب: ${o.order_number}</p>
            <p>🔢 عدد المنتجات: ${o.total_quantity}</p>
            <p>💳 طريقة الدفع: ${o.payment_method}</p>
            <p>🕒 ${o.created_at}</p>
        `;

        if(o.status==='غير مدفوع'){
            pendingCount++;
            const btn = document.createElement('button');
            btn.classList.add('pay-btn');
            btn.textContent='✅ تم الدفع';
            btn.onclick = ()=>updateStatus(o.id,'مدفوع',div);
            div.appendChild(btn);
            pendingContainer.appendChild(div);
        } else if(o.status==='مدفوع'){
            paidCount++;
            const btn = document.createElement('button');
            btn.classList.add('deliver-btn');
            btn.textContent='🚚 تم التوصيل';
            btn.onclick = ()=>updateStatus(o.id,'تم التوصيل',div);
            div.appendChild(btn);
            paidContainer.appendChild(div);
        } else if(o.status==='تم التوصيل'){
            deliveredCount++;
            deliveredContainer.appendChild(div);
        }
    });

    document.getElementById('pendingCount').textContent = pendingCount;
    document.getElementById('paidCount').textContent = paidCount;
    document.getElementById('deliveredCount').textContent = deliveredCount;
}

// تحديث حالة الطلب بدون إعادة تحميل
async function updateStatus(id, status, orderDiv){
    const res = await fetch('update_status.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id,status})
    });
    const data = await res.json();
    if(data.status==='success'){
        orderDiv.classList.add('fadeOut');
        setTimeout(()=>loadOrders(),300);
    } else {
        alert('حدث خطأ أثناء تحديث الحالة');
    }
}

// تحميل الطلبات عند فتح الصفحة
loadOrders();
</script>

</body>
</html>