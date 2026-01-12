<?php
namespace App\Controllers;

use App\Models\Favorito;
use App\Models\Producto;
use App\Config\SpotifyAPI;

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
        $id_spotify_o_local = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;

        if (!$id_spotify_o_local) {
            echo json_encode(['success' => false, 'message' => 'ID de producto no válido']);
            exit;
        }

        $modeloProducto = new Producto();
        $id_producto_local = null;

        $productoExistente = $modeloProducto->obtenerPorSpotifyId($id_spotify_o_local);
        
        if ($productoExistente) {
            $id_producto_local = $productoExistente['id_producto'];
        } else {
            $porId = $modeloProducto->obtenerPorId($id_spotify_o_local);
            if ($porId) {
                $id_producto_local = $porId['id_producto'];
            } else {
                $spotify = new SpotifyAPI();
                $album = $spotify->obtenerAlbum($id_spotify_o_local);
                
                if ($album && isset($album['id'])) {
                    $hash = crc32($album['id']);
                    $popularidad = 50 + ($hash % 46);
                    
                    $datosNuevo = [
                        'titulo' => $album['name'],
                        'artista' => $album['artists'][0]['name'],
                        'imagen_url' => isset($album['images'][0]['url']) ? $album['images'][0]['url'] : null,
                        'popularidad' => $popularidad,
                        'id_spotify' => $album['id']
                    ];
                    
                    $id_producto_local = $modeloProducto->crear($datosNuevo);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Producto no encontrado en Spotify']);
                    exit;
                }
            }
        }

        if (!$id_producto_local) {
             echo json_encode(['success' => false, 'message' => 'Error al procesar el producto']);
             exit;
        }

        $modeloFavorito = new Favorito();
        
        if ($modeloFavorito->esFavorito($id_usuario, $id_producto_local)) {
            if ($modeloFavorito->eliminar($id_usuario, $id_producto_local)) {
                echo json_encode(['success' => true, 'action' => 'removed']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
            }
        } else {
            if ($modeloFavorito->agregar($id_usuario, $id_producto_local)) {
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
