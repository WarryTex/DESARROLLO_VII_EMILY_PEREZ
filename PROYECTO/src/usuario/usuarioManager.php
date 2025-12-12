<?php
class UsuarioManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registrar($nombre, $telefono, $email, $direccion, $contrasena) {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (nombre_completo, telefono, email, direccion, contrasena) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre, $telefono, $email, $direccion, $contrasena_hash]);
    }

    public function login($email, $contrasena) {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        return false;
    }
}
?>