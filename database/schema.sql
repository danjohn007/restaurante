-- Database schema for Restaurant Administration System
-- MySQL 5.7 compatible

CREATE DATABASE IF NOT EXISTS restaurante_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurante_db;

-- Users and roles table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'waiter', 'cashier', 'chef') NOT NULL DEFAULT 'waiter',
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tables in restaurant
CREATE TABLE restaurant_tables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_number VARCHAR(10) UNIQUE NOT NULL,
    capacity INT NOT NULL DEFAULT 4,
    position_x INT DEFAULT 0,
    position_y INT DEFAULT 0,
    status ENUM('available', 'occupied', 'reserved', 'cleaning') DEFAULT 'available',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Customers
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    loyalty_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Menu categories
CREATE TABLE menu_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu items
CREATE TABLE menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    cost DECIMAL(10,2) DEFAULT 0,
    image_url VARCHAR(500),
    is_available BOOLEAN DEFAULT TRUE,
    preparation_time INT DEFAULT 15, -- minutes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE CASCADE
);

-- Reservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    table_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    party_size INT NOT NULL,
    status ENUM('pending', 'confirmed', 'seated', 'completed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id) ON DELETE CASCADE
);

-- Orders
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_id INT,
    customer_id INT,
    waiter_id INT NOT NULL,
    order_type ENUM('dine_in', 'takeout', 'delivery') DEFAULT 'dine_in',
    status ENUM('pending', 'preparing', 'ready', 'served', 'paid', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10,2) DEFAULT 0,
    tax DECIMAL(10,2) DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (waiter_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    special_instructions TEXT,
    status ENUM('pending', 'preparing', 'ready', 'served') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Inventory categories
CREATE TABLE inventory_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inventory items
CREATE TABLE inventory_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    unit VARCHAR(50) NOT NULL, -- kg, units, liters, etc.
    current_stock DECIMAL(10,3) DEFAULT 0,
    min_stock DECIMAL(10,3) DEFAULT 0,
    max_stock DECIMAL(10,3) DEFAULT 0,
    unit_cost DECIMAL(10,2) DEFAULT 0,
    supplier VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES inventory_categories(id) ON DELETE CASCADE
);

-- Inventory movements
CREATE TABLE inventory_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    movement_type ENUM('in', 'out', 'adjustment') NOT NULL,
    quantity DECIMAL(10,3) NOT NULL,
    unit_cost DECIMAL(10,2) DEFAULT 0,
    reference_type ENUM('purchase', 'sale', 'waste', 'adjustment') NOT NULL,
    reference_id INT,
    notes TEXT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Payment methods
CREATE TABLE payment_methods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Payments
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    payment_method_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    cashier_id INT NOT NULL,
    transaction_reference VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE CASCADE,
    FOREIGN KEY (cashier_id) REFERENCES users(id) ON DELETE CASCADE
);

-- System settings
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data
INSERT INTO users (email, password, first_name, last_name, role) VALUES
('admin@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Sistema', 'admin'),
('manager@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gerente', 'Principal', 'manager'),
('mesero@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez', 'waiter'),
('cajero@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'González', 'cashier'),
('chef@restaurante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Rodríguez', 'chef');

INSERT INTO restaurant_tables (table_number, capacity, position_x, position_y) VALUES
('M1', 4, 100, 100),
('M2', 4, 250, 100),
('M3', 2, 400, 100),
('M4', 6, 100, 250),
('M5', 4, 250, 250),
('M6', 8, 400, 250),
('M7', 2, 100, 400),
('M8', 4, 250, 400);

INSERT INTO menu_categories (name, description, sort_order) VALUES
('Entradas', 'Aperitivos y entradas', 1),
('Platos Principales', 'Platos fuertes del menú', 2),
('Bebidas', 'Bebidas frías y calientes', 3),
('Postres', 'Dulces y postres', 4);

INSERT INTO menu_items (category_id, name, description, price, cost, preparation_time) VALUES
(1, 'Nachos con Queso', 'Nachos crujientes con queso fundido y jalapeños', 8.99, 3.50, 10),
(1, 'Alitas Buffalo', '8 alitas de pollo en salsa buffalo', 12.99, 5.00, 15),
(2, 'Hamburguesa Clásica', 'Hamburguesa de carne con lechuga, tomate y queso', 15.99, 7.00, 20),
(2, 'Pollo a la Plancha', 'Pechuga de pollo con vegetales al vapor', 18.99, 8.50, 25),
(3, 'Coca Cola', 'Refresco de cola 355ml', 2.99, 1.00, 2),
(3, 'Agua Natural', 'Agua purificada 500ml', 1.99, 0.50, 1),
(4, 'Pastel de Chocolate', 'Rebanada de pastel de chocolate casero', 6.99, 2.50, 5),
(4, 'Helado de Vainilla', '2 bolas de helado de vainilla', 4.99, 1.50, 3);

INSERT INTO inventory_categories (name, description) VALUES
('Carnes', 'Carnes y productos cárnicos'),
('Verduras', 'Vegetales y hortalizas'),
('Lácteos', 'Productos lácteos'),
('Bebidas', 'Bebidas y refrescos'),
('Condimentos', 'Especias y condimentos');

INSERT INTO inventory_items (category_id, name, unit, current_stock, min_stock, max_stock, unit_cost) VALUES
(1, 'Carne de Res', 'kg', 50.0, 10.0, 100.0, 12.50),
(1, 'Pollo', 'kg', 30.0, 8.0, 80.0, 8.75),
(2, 'Lechuga', 'kg', 15.0, 5.0, 30.0, 2.50),
(2, 'Tomate', 'kg', 20.0, 5.0, 40.0, 3.00),
(3, 'Queso Cheddar', 'kg', 10.0, 3.0, 25.0, 15.00),
(4, 'Coca Cola', 'unidades', 200, 50, 500, 1.20);

INSERT INTO payment_methods (name) VALUES
('Efectivo'),
('Tarjeta de Crédito'),
('Tarjeta de Débito'),
('Transferencia');

INSERT INTO settings (setting_key, setting_value, description) VALUES
('restaurant_name', 'Mi Restaurante', 'Nombre del restaurante'),
('tax_rate', '16', 'Porcentaje de IVA'),
('currency', 'MXN', 'Moneda del sistema'),
('timezone', 'America/Mexico_City', 'Zona horaria'),
('opening_time', '08:00', 'Hora de apertura'),
('closing_time', '22:00', 'Hora de cierre');

-- Note: Default password for all users is "password123"