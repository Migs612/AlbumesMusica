<?php
namespace App\Models;

use App\Config\BaseDeDatos;

class Modelo {
    protected $conexion;

    public function __construct() {
        $this->conexion = BaseDeDatos::obtenerInstancia()->obtenerConexion();
    }
}
