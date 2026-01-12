<?php
namespace App\Config;

use PDO;
use PDOException;

class BaseDeDatos {
    private static $instancia = null;
    private $connection;
    
    private $host;
    private $nombre_bd;
    private $usuario;
    private $contrasena;

    private function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->nombre_bd = $_ENV['DB_NAME'] ?? '';
        $this->usuario = $_ENV['DB_USER'] ?? 'root';
        $this->contrasena = $_ENV['DB_PASS'] ?? '';
        
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->nombre_bd};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->usuario, $this->contrasena, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function obtenerConexion() {
        return $this->connection;
    }
}
