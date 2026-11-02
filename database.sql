CREATE DATABASE IF NOT EXISTS cleanver_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cleanver_admin;

CREATE TABLE categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id INT UNSIGNED NULL,
 name VARCHAR(150) NOT NULL,
 sku VARCHAR(60) NOT NULL UNIQUE,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 stock INT NOT NULL DEFAULT 0,
 description TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
 INDEX idx_products_name(name)
) ENGINE=InnoDB;

CREATE TABLE stock_transactions (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 product_id INT UNSIGNED NOT NULL,
 type ENUM('masuk','keluar') NOT NULL,
 quantity INT UNSIGNED NOT NULL,
 note VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_transactions_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
 INDEX idx_transactions_created(created_at)
) ENGINE=InnoDB;

INSERT INTO categories(name,description) VALUES
('Shoe Cleaner','Produk pembersih sepatu'),
('Cleaning Tools','Alat bantu membersihkan sepatu'),
('Shoe Care','Produk perawatan sepatu');

INSERT INTO products(name,sku,category_id,price,stock,description) VALUES
('Cleanver Foam Cleaner','CLN-001',1,35000,24,'Cairan pembersih sepatu tanpa perlu dibilas.'),
('Cleanver Suede Eraser','CLN-002',2,18000,15,'Penghapus khusus untuk material suede.'),
('Cleanver 2-in-1 Brush','CLN-003',2,25000,12,'Sikat multifungsi untuk perawatan sepatu.'),
('Cleanver 4-in-1 Brush','CLN-004',2,35000,8,'Sikat dengan beberapa fungsi pembersihan.'),
('Cleanver Shoe Fragrance','CLN-005',3,22000,4,'Pewangi untuk menjaga kesegaran sepatu.'),
('Cleanver Microfiber','CLN-006',2,12000,30,'Kain microfiber untuk mengelap sepatu.');
