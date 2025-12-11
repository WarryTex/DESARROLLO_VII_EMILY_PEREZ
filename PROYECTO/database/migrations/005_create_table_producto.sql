CREATE TABLE producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT,
    nombre_producto VARCHAR(200) NOT NULL,
    descripcion_producto TEXT,
    precio_producto DECIMAL(10,2) NOT NULL,
    imagen_producto VARCHAR(255),
    stock_producto INT DEFAULT 0,
    personalizable INT DEFAULT 0,
    dimensiones VARCHAR(50),
    status_producto INT DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);