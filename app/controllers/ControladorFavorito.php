<?php
namespace App\Controllers;

use App\Models\Favorito;
use App\Models\Producto;

class ControladorFavorito {

    public function toggle() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;

        if (!$id_producto) {
            echo json_encode(['success' => false, 'message' => 'ID de producto no válido']);
            exit;
        }

        $modeloFavorito = new Favorito();
        
        if ($modeloFavorito->esFavorito($id_usuario, $id_producto)) {
            if ($modeloFavorito->eliminar($id_usuario, $id_producto)) {
                echo json_encode(['success' => true, 'action' => 'removed']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
            }
        } else {
            if ($modeloFavorito->agregar($id_usuario, $id_producto)) {
                echo json_encode(['success' => true, 'action' => 'added']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar']);
            }
        }
    }

    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?p=login');
            exit;
        }

        $id_usuario = $_SESSION['usuario_id'];
        $modeloFavorito = new Favorito();
        $favoritos = $modeloFavorito->obtenerPorUsuario($id_usuario);

        require_once 'app/views/favoritos.php';
    }
}
