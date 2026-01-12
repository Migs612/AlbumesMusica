<?php
namespace App\Config;

use PDO;
use PDOException;

class BaseDeDatos {
    private $host = 'srv1687.hstgr.io';
    private $nombre_bd = 'u336643015_LavenderTunes';
    private $usuario = 'u336643015_LavenderAdmin';
    private $contrasena = '8DbboJZT7W628z';
    public $conexion;

    public function obtenerConexion() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->nombre_bd};charset=utf8mb4";
            $this->conexion = new PDO($dsn, $this->usuario, $this->contrasena, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            return $this->conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
