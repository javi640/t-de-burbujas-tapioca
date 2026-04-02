CREATE DATABASE IF NOT EXISTS bubble_tea;
USE bubble_tea;

-- EMPLEADOS
CREATE TABLE empleados (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(100) NOT NULL,
    apellido         VARCHAR(100) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    activo           BOOLEAN NOT NULL DEFAULT TRUE
);

-- CLIENTES
CREATE TABLE clientes (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(100) NOT NULL,
    apellido            VARCHAR(100) NOT NULL,
    email               VARCHAR(150) UNIQUE,
    telefono            VARCHAR(20),
    puntos_lealtad      INT DEFAULT 0,
    fecha_registro      DATE NOT NULL DEFAULT (CURRENT_DATE)
);

-- CATEGORÍAS
CREATE TABLE categorias (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(80) NOT NULL,
    descripcion VARCHAR(200)
);

-- PRODUCTOS
CREATE TABLE productos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre       VARCHAR(150) NOT NULL,
    precio       DECIMAL(10,2) NOT NULL, 
    stock_actual INT DEFAULT 0,
    disponible   BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- PEDIDOS
CREATE TABLE pedidos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id    INT NULL,
    empleado_id   INT NOT NULL,
    fecha_hora     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado        ENUM('pendiente', 'preparando', 'entregado', 'cancelado') DEFAULT 'entregado',
    metodo_pago   ENUM('efectivo','tarjeta','transferencia','app') NOT NULL,
    FOREIGN KEY (cliente_id)  REFERENCES clientes(id),
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

-- DETALLE DE PEDIDO
CREATE TABLE detalle_pedido (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id       INT NOT NULL,
    producto_id     INT NOT NULL,
    cantidad        INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    personalizacion VARCHAR(255), 
    subtotal        DECIMAL(10,2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
    FOREIGN KEY (pedido_id)  REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);