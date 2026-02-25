
CREATE DATABASE store_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE store_db;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_address TEXT NOT NULL,
    total_quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_details LONGTEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
ADD COLUMN status ENUM('غير مدفوع','مدفوع','تم التوصيل') NOT NULL DEFAULT 'غير مدفوع';

);