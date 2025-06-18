USE padel_pro_gsd;

drop TABLE if exists jugadores;
CREATE TABLE jugadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    categoria ENUM('masculino', 'femenino', 'mixto') NOT NULL,
    mano_dominante ENUM('diestra', 'zurda') NOT NULL,
    nivel ENUM('principiante', 'intermedio', 'avanzado') NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);