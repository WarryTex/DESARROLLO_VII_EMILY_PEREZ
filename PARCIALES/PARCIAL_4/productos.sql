CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    categoria VARCHAR (80) NOT NULL,
    precio DECIMAL (10,2) NOT NULL,
    cantidad INT (150) NOT NULL,
    fecha_registro DATETIME
    );