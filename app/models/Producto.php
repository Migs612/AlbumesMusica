<?php
namespace App\Models;

use App\Config\BaseDeDatos;
use PDO;

class Producto {
    private $conexion;
    private $tabla = 'productos';

    public $id_producto;
    public $titulo;
    public $artista;
    public $imagen_url;
    public $popularidad;
    public $precio;

    public function __construct() {
        $base_datos = new BaseDeDatos();
        $this->conexion = $base_datos->obtenerConexion();
    }

    
    public static function calcularPrecioInicial($puntuacion_popularidad) {
        
        $precio_base = 12.99;
        $factor_popularidad = ($puntuacion_popularidad / 100) * 7.00;
        return round($precio_base + $factor_popularidad, 2);
    }

    public function obtenerTodos() {
        $consulta = "SELECT * FROM " . $this->tabla;
        
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $consulta = "SELECT * FROM " . $this->tabla . " WHERE id_producto = :id LIMIT 1";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorSpotifyId($id_spotify) {
        $consulta = "SELECT * FROM " . $this->tabla . " WHERE id_spotify = :id LIMIT 1";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id_spotify);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    
    public function crear($datos) {
        
        if (isset($datos['id_spotify'])) {
            $existente = $this->obtenerPorSpotifyId($datos['id_spotify']);
            if ($existente) return $existente['id_producto'];
        }

        $precio = isset($datos['precio']) ? $datos['precio'] : self::calcularPrecioInicial($datos['popularidad']);
        
        
        $id_genero = isset($datos['id_genero']) ? $datos['id_genero'] : 2; 

        $consulta = "INSERT INTO " . $this->tabla . " 
                    (titulo, artista, imagen_url, popularidad, precio, id_spotify, id_genero)
                    VALUES (:titulo, :artista, :imagen, :pop, :precio, :spotify, :genero)";
        
        $sentencia = $this->conexion->prepare($consulta);
        
        $precio = isset($datos['precio']) ? $datos['precio'] : self::calcularPrecioInicial($datos['popularidad']);
        
        $consulta = "INSERT INTO " . $this->tabla . " 
                    (titulo, artista, imagen_url, popularidad, precio, id_spotify)
                    VALUES (:titulo, :artista, :imagen, :pop, :precio, :spotify)";
        
        $sentencia = $this->conexion->prepare($consulta);
        
        $sentencia->bindParam(':titulo', $datos['titulo']);
        $sentencia->bindParam(':artista', $datos['artista']);
        $sentencia->bindParam(':imagen', $datos['imagen_url']);
        $sentencia->bindParam(':pop', $datos['popularidad']);
        $sentencia->bindParam(':precio', $precio);
        $sentencia->bindParam(':spotify', $datos['id_spotify']);

        if ($sentencia->execute()) {
            return $this->conexion->lastInsertId();
        }
        return false;
    }
}