CREATE TABLE pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_pedido DECIMAL(10,2) NOT NULL,
    estado_pedido VARCHAR(50) DEFAULT 'pendiente',
    descripcion_pedido TEXT,
    codigo_envio VARCHAR(100),
    direccion_envio TEXT,
    descuento_aplicado DECIMAL(10,2) DEFAULT 0,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);