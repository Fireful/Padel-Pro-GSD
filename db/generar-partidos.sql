-- Tabla principal de partidos
CREATE TABLE partidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    torneo_id INT NOT NULL,
    fecha DATE,
    hora TIME,
    campo VARCHAR(50),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (torneo_id) REFERENCES torneos(id) ON DELETE CASCADE
);

-- Equipos o jugadores por partido
CREATE TABLE partidos_equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    partido_id INT NOT NULL,
    equipo_id INT NOT NULL,
    resultado VARCHAR(20), -- Ej: "3-6", "7-5", etc.
    FOREIGN KEY (partido_id) REFERENCES partidos(id) ON DELETE CASCADE,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);