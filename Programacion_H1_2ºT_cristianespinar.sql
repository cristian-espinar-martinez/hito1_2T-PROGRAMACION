CREATE DATABASE IF NOT EXISTS StreamWeb;
USE StreamWeb;

-- Tabla de planes base
CREATE TABLE IF NOT EXISTS plan_base (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('Básico', 'Estándar', 'Premium') NOT NULL UNIQUE,
    precio DECIMAL(5,2) NOT NULL
);

-- Tabla de paquetes adicionales
CREATE TABLE IF NOT EXISTS paquetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('Deporte', 'Cine', 'Infantil') NOT NULL UNIQUE,
    precio DECIMAL(5,2) NOT NULL
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    edad INT NOT NULL CHECK (edad > 0),
    plan_base_id INT NOT NULL,
    duracion ENUM('Mensual', 'Anual') NOT NULL,
    FOREIGN KEY (plan_base_id) REFERENCES plan_base(id) ON DELETE CASCADE
);

-- Tabla  para paquetes seleccionados por los usuarios
CREATE TABLE IF NOT EXISTS usuario_paquetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    paquete_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (paquete_id) REFERENCES paquetes(id) ON DELETE CASCADE
);

-- ----------------------------
-- INSERTS
-- ----------------------------

-- Insertar registros en plan_base
INSERT INTO plan_base (nombre, precio) VALUES
('Básico', 9.99),
('Estándar', 13.99),
('Premium', 17.99);

-- Insertar registros en paquetes
INSERT INTO paquetes (nombre, precio) VALUES
('Deporte', 6.99),
('Cine', 7.99),
('Infantil', 4.99);

-- Insertar 10 registros en usuarios
INSERT INTO usuarios (nombre, apellidos, correo, edad, plan_base_id, duracion) VALUES
('Carlos', 'Gómez', 'carlos.gomez@email.com', 25, 1, 'Mensual'),
('María', 'Fernández', 'maria.fernandez@email.com', 32, 2, 'Anual'),
('Javier', 'López', 'javier.lopez@email.com', 19, 3, 'Mensual'),
('Ana', 'Martínez', 'ana.martinez@email.com', 27, 2, 'Anual'),
('Elena', 'Sánchez', 'elena.sanchez@email.com', 16, 1, 'Mensual'),
('Luis', 'Ramírez', 'luis.ramirez@email.com', 35, 3, 'Anual'),
('Diana', 'Hernández', 'diana.hernandez@email.com', 40, 2, 'Mensual'),
('Pedro', 'Torres', 'pedro.torres@email.com', 22, 1, 'Anual'),
('Sara', 'Díaz', 'sara.diaz@email.com', 17, 1, 'Mensual'),
('Andrés', 'Castro', 'andres.castro@email.com', 29, 3, 'Anual');

-- Insertar 10 registros en usuario_paquetes (relación usuarios - paquetes adicionales)
inserT INTO usuario_paquetes (usuario_id, paquete_id) VALUES
(1, 2),  -- Carlos - Cine
(2, 1),  -- María - Deporte
(3, 3),  -- Javier - Infantil
(4, 1),  -- Ana - Deporte
(5, 3),  -- Elena - Infantil (menor de 18, solo Infantil permitido)
(6, 2),  -- Luis - Cine
(7, 1),  -- Diana - Deporte
(8, 2),  -- Pedro - Cine
(9, 3),  -- Sara - Infantil (menor de 18, solo Infantil permitido)
(10, 1); -- Andrés - Deporte


