CREATE TABLE descuento (
    id_descuento INT AUTO_INCREMENT PRIMARY KEY,
    nombre_descuento VARCHAR(100) NOT NULL,
    tipo_descuento VARCHAR(50),
    valor_descuento DECIMAL(10,2),
    fecha_inicio_descuento DATETIME,
    fecha_final_descuento DATETIME,
    activo INT DEFAULT 1
);