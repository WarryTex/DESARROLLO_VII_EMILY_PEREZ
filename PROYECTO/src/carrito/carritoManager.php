<?php
class Carrito {
 
    // Para agregar un nuevo producto al carrito
    public static function agregar($id_producto) {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]++;
        } else {
            $_SESSION['carrito'][$id_producto] = 1;
        }
    }
    
    //Obtener el carrito para pedidos en caso tal se de el caso
    public static function obtener() {
        return $_SESSION['carrito'] ?? [];
    }

    //Para eliminar el carrito 
    public static function eliminar($id_producto) {
        unset($_SESSION['carrito'][$id_producto]);
    }
    
    public static function contar() {
        $total = 0;
        foreach (self::obtener() as $cantidad) {
            $total += $cantidad;
        }
        return $total;
    }

    //Vaciar el carrito
    public static function vaciar() {
    $_SESSION['carrito'] = [];
}
}
?>