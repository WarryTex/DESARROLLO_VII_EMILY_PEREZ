CREATE TABLE pago (
    id_page INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    monto_pago DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50),
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_pago VARCHAR(50) DEFAULT 'pendiente',
    numero_transaccion VARCHAR(100),
    FOREIGN KEY (id_pedido) REFERENCES pedido (id_pedido)
);
