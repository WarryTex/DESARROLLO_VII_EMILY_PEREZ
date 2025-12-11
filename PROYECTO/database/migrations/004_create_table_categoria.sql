CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    tipo_categoria VARCHAR(100) NOT NULL  
);

INSERT INTO categoria (id_categoria, tipo_categoria) VALUES
(1, 'Niña'),
(2, 'Niño');