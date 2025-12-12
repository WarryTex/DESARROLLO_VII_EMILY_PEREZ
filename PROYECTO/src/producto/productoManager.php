<?php
require_once BASE_PATH . 'src/producto/producto.php';

class ProductoManager {
    private $db;

    //Conexion a la base de datos
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    //Consultamos todos los productos que tenemos
    public function consultar_todo_los_productos() {
        $stmt = $this->db->query("SELECT * FROM producto ORDER BY id_producto DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //Esta es la crear un nuevo producto
    public function crearProducto($id_categoria, $nombre, $descripcion, $precio, $archivo, $stock, $personalizable, $dimensiones, $status) {
        $imagen = Producto::subirImagen($archivo);
        $sql = "INSERT INTO producto (id_categoria, nombre_producto, descripcion_producto, precio_producto, imagen_producto, stock_producto, personalizable, dimensiones, status_producto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_categoria,$nombre,$descripcion,$precio,$imagen,$stock,$personalizable,$dimensiones,$status]);
    }

    //

    public function actualizarProducto($id, $id_categoria, $nombre, $descripcion, $precio, $archivo, $stock, $personalizable, $dimensiones, $status) {
        $producto_actual = $this->obtener_producto($id);
        
        if ($archivo && $archivo['error'] === 0) {
            if ($producto_actual['imagen_producto']) {
                Producto::eliminarImagen($producto_actual['imagen_producto']);
            }
            $imagen = Producto::subirImagen($archivo);
        } else {
            $imagen = $producto_actual['imagen_producto'];
        }

        $sql = "UPDATE producto SET id_categoria = ?, nombre_producto = ?, descripcion_producto = ?, precio_producto = ?, imagen_producto = ?, stock_producto = ?, personalizable = ?, dimensiones = ?, status_producto = ? WHERE id_producto = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_categoria, $nombre,$descripcion,$precio,$imagen,$stock,$personalizable,$dimensiones,$status,$id]);
    }

    public function obtener_producto($id) {
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    
    public function eliminar_producto($id) {
        $producto = $this->obtener_producto($id);
        
        if ($producto && $producto['imagen_producto']) {
            Producto::eliminarImagen($producto['imagen_producto']);
        }

        $stmt = $this->db->prepare("DELETE FROM producto WHERE id_producto = ?");
        $stmt->execute([$id]);
    }
  
    //Filtrar las categorias si quieres todos los productos,o solo los de niñas o niños
    public function consultar_productos_por_categoria($id_categoria = null) {
        
    if ($id_categoria === null) {
        // Todos los productos
        $stmt = $this->db->query("SELECT * FROM producto WHERE status_producto = 1 ORDER BY id_producto DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Productos de una categoría específica
        $stmt = $this->db->prepare("SELECT * FROM producto WHERE id_categoria = ? AND status_producto = 1 ORDER BY id_producto DESC");
        $stmt->execute([$id_categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
}
?>

