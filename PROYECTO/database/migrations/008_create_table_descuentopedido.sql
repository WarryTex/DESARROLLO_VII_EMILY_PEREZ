CREATE TABLE descuento_producto (
    id_descuento_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_descuento INT,
    id_producto INT,
    FOREIGN KEY (id_descuento) REFERENCES descuento(id_descuento),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);