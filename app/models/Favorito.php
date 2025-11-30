<?php
namespace App\Models;

use App\Config\BaseDeDatos;
use PDO;

class Favorito {
    private $conn;
    private $table = 'favoritos';

    public function __construct() {
        $database = new BaseDeDatos();
        $this->conn = $database->obtenerConexion();
    }

    public function agregar($id_usuario, $id_producto) {
        $query = "INSERT INTO " . $this->table . " (id_usuario, id_producto) VALUES (:id_usuario, :id_producto)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function eliminar($id_usuario, $id_producto) {
        $query = "DELETE FROM " . $this->table . " WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        return $stmt->execute();
    }

    public function esFavorito($id_usuario, $id_producto) {
        $query = "SELECT id_favorito FROM " . $this->table . " WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT p.*, f.fecha_agregado 
                  FROM " . $this->table . " f
                  JOIN productos p ON f.id_producto = p.id_producto
                  WHERE f.id_usuario = :id_usuario
                  ORDER BY f.fecha_agregado DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
