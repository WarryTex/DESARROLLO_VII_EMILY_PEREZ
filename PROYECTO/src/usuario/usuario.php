<?php
class Usuario {
    public $id_usuario;
    public $nombre_completo;
    public $telefono;
    public $email;
    public $direccion;
    public $contrasena;

    public function __construct($data) {
        $this->id_usuario = $data['id_usuario'];
        $this->nombre_completo = $data['nombre_completo'];
        $this->telefono = $data['telefono'];
        $this->email = $data['email'];
        $this->direccion = $data['direccion'];
        $this->contrasena = $data['contrasena'];
    }
}
?>