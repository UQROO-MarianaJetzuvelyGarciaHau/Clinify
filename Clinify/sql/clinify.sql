CREATE DATABASE IF NOT EXISTS clinify;
USE clinify;

CREATE TABLE planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    periodo VARCHAR(50) NOT NULL,
    almacenamiento VARCHAR(100) NOT NULL,
    cantidad_estudios VARCHAR(100) NOT NULL,
    soporte VARCHAR(100) NOT NULL,
    estado TINYINT(1) DEFAULT 1,
    es_mas_vendido TINYINT(1) DEFAULT 0
);

INSERT INTO planes 
(nombre, descripcion, precio, periodo, almacenamiento, cantidad_estudios, soporte, estado, es_mas_vendido)
VALUES
('Básico', 'Ideal para usuarios que desean organizar sus estudios personales.', 99.00, 'Mensual', 'Almacenamiento básico', 'Hasta 20 estudios', 'Soporte por correo', 1, 0),
('Familiar', 'Perfecto para familias que desean mantener varios perfiles organizados.', 199.00, 'Mensual', 'Almacenamiento familiar', 'Hasta 100 estudios', 'Soporte prioritario', 1, 1),
('Premium', 'Para usuarios que buscan mayor capacidad y funciones avanzadas.', 299.00, 'Mensual', 'Mayor almacenamiento', 'Estudios ilimitados', 'Soporte avanzado', 0, 0);

CREATE TABLE caracteristicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    icono VARCHAR(50) NOT NULL,
    estado TINYINT(1) DEFAULT 1
);

INSERT INTO caracteristicas (titulo, descripcion, icono, estado) VALUES
('Organización de estudios', 'Permite mantener los estudios médicos ordenados en un solo lugar.', '📁', 1),
('Acceso rápido', 'Facilita consultar información médica importante desde cualquier dispositivo.', '⚡', 1),
('Historial centralizado', 'Ayuda a reunir información médica dispersa en una plataforma sencilla.', '🩺', 1),
('Mayor seguridad', 'Reduce el riesgo de perder documentos físicos importantes.', '🔒', 1);

CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL,
    telefono VARCHAR(20),
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rol VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL
);

INSERT INTO equipo (nombre, rol, descripcion)
VALUES

(
'Diego Aldair Tillit Ihuit',
'Diseño y programación',
'Responsable del desarrollo del frontend, apoyo en el backend y conexión con la base de datos.'
),

(
'Mariana Jetzuvely Garcia Hau',
'Diseño y programación',
'Apoyo en el diseño visual, backend, programación y conexión con la base de datos.'
),

(
'Paola Juliette Tun Gómez',
'Documentación y análisis',
'Encargada de la documentación, análisis de requisitos y organización del proyecto.'
),

(
'Abeline Stacey Ramírez',
'Pruebas y validación',
'Responsable de las pruebas funcionales y validación del sistema.'
);

CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO administradores (nombre, correo, password)
VALUES ('Administrador Clinify', 'admin@clinify.com', '123456');