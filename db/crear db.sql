CREATE DATABASE padel_pro_gsd;

USE padel_pro_gsd;

CREATE TABLE torneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    categoria ENUM('masculino', 'femenino', 'mixto') NOT NULL,
    formato ENUM('liguilla', 'eliminatoria', 'round-robin') NOT NULL,
    max_participantes INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);