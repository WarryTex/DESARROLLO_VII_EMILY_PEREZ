<?php
class producto {
    public $id_producto;
    public $id_categoria;
    public $nombre_producto;
    public $descripcion_producto;
    public $precio_producto;
    public $imagen_producto;
    public $stock_producto;
    public $personalizable;
    public $dimensiones;
    public $status_producto;

    // Constructor para crear un objeto producto a partir de un array de datos
    public function __construct($data) {
        $this->id_producto = $data['id_producto'];
        $this->id_categoria = $data['id_categoria'];
        $this->nombre_producto = $data['nombre_producto'];
        $this->descripcion_producto = $data['descripcion_producto'];
        $this->precio_producto = $data['precio_producto'];
        $this->imagen_producto = $data['imagen_producto'];
        $this->stock_producto = $data['stock_producto'];
        $this->personalizable = $data['personalizable'];
        $this->dimensiones = $data['dimensiones'];
        $this->status_producto = $data['status_producto'];
    }
       
        //Función para subir imagen a la carpeta del proyecto
 public static function subirImagen($archivo) {
        if (!$archivo || $archivo['error'] !== 0) return null;
        
        $directorio = BASE_PATH . 'public/assets/img/';
        if (!file_exists($directorio)) mkdir($directorio, 0755, true);
        
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return null;
        
        $nombre_imagen = time() . '_' . uniqid() . '.' . $extension;
        $ruta_completa = $directorio . $nombre_imagen;
        
        return move_uploaded_file($archivo['tmp_name'], $ruta_completa) ? $nombre_imagen : null;
    }

    public static function eliminarImagen($nombre_imagen) {
        if (!$nombre_imagen) return;
        
        $ruta = BASE_PATH . 'public/assets/img/' . $nombre_imagen;
        if (file_exists($ruta)) unlink($ruta);
    }

    public function getImagenUrl() {
        $imagen = $this->imagen_producto ?: 'no-image.png';
        return BASE_URL . '/public/assets/img/' . $imagen;
    }
}

?>
