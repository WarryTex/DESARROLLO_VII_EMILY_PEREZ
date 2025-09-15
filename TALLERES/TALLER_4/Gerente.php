<?php
require_once "Empleado.php";
require_once "Evaluable.php";

class Gerente extends Empleado implements Evaluable {
    private $departamento;
    private $bono = 0;

    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function asignarBono($monto) {
        $this->bono = $monto;
    }

    public function getSalarioTotal() {
        return $this->getSalarioBase() + $this->bono;
    }

    public function evaluarDesempenio() {
        return "El gerente " . $this->getNombre() . " ha gestionado eficazmente el departamento de " . $this->departamento . ".";
    }
}
