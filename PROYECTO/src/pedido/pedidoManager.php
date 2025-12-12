<?php
class PedidoManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtener_pedidos_usuario($id_usuario) {
        $sql = "SELECT * FROM pedido WHERE id_usuario = ? ORDER BY fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_detalle_pedido($id_pedido) {
        $sql = "SELECT dp.*, p.nombre_producto, p.imagen_producto 
                FROM detalle_pedido dp 
                JOIN producto p ON dp.id_producto = p.id_producto 
                WHERE dp.id_pedido = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear_pedido($id_usuario, $total, $direccion_envio) {
    $sql = "INSERT INTO pedido (id_usuario, fecha_pedido, total_pedido, estado_pedido, direccion_envio) VALUES (?, NOW(), ?, 'Pendiente', ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id_usuario, $total, $direccion_envio]);
    return $this->db->lastInsertId();
}

    public function agregar_detalle($id_pedido, $id_producto, $cantidad, $precio) {
    $subtotal = $cantidad * $precio;
    $sql = "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id_pedido, $id_producto, $cantidad, $precio, $subtotal]);
}
}
?>