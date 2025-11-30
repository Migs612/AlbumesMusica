<?php
namespace App\Config;

use PDO;
use PDOException;

class BaseDeDatos {
    private $host = 'localhost';
    private $nombre_bd = 'tienda';
    private $usuario = 'root';
    private $contrasena = '';
    public $conexion;

    public function obtenerConexion() {
        $this->conexion = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->nombre_bd . ";charset=utf8mb4";
            $this->conexion = new PDO($dsn, $this->usuario, $this->contrasena);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $excepcion) {
            echo "Error de conexión: " . $excepcion->getMessage();
        }

        return $this->conexion;
    }
}
