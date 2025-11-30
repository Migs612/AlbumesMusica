<?php
namespace App\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Config\SpotifyAPI;

class ControladorPedido {

    public function agregarCarrito() {
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $id = $_POST['id_producto'];
        $titulo = $_POST['titulo'];
        $precio = $_POST['precio'];
        
        
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id_producto'] == $id) {
                $item['cantidad']++;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $_SESSION['carrito'][] = [
                'id_producto' => $id,
                'titulo' => $titulo,
                'precio' => $precio,
                'cantidad' => 1
            ];
        }

        if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Producto agregado correctamente']);
            exit;
        }

        header('Location: index.php?p=carrito');
    }

    public function actualizarCarrito() {
        if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
            $id = $_POST['id_producto'];
            $cantidad = intval($_POST['cantidad']);

            if ($cantidad > 0) {
                foreach ($_SESSION['carrito'] as &$item) {
                    if ($item['id_producto'] == $id) {
                        $item['cantidad'] = $cantidad;
                        break;
                    }
                }
            }
        }
        header('Location: index.php?p=carrito');
    }

    public function eliminarDelCarrito() {
        if (isset($_POST['id_producto'])) {
            $id = $_POST['id_producto'];
            
            foreach ($_SESSION['carrito'] as $key => $item) {
                if ($item['id_producto'] == $id) {
                    unset($_SESSION['carrito'][$key]);
                    break;
                }
            }
            
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }
        header('Location: index.php?p=carrito');
    }

    public function verCarrito() {
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        require_once '../app/views/carrito.php';
    }

    public function procesarPedido() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?p=login');
            exit;
        }

        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        if (empty($carrito)) {
            header('Location: index.php?p=catalogo');
            exit;
        }

        
        $productoModel = new Producto();
        $spotifyApi = new SpotifyAPI();
        $carrito_procesado = [];
        $total = 0;

        foreach ($carrito as $item) {
            $id_producto_final = $item['id_producto'];

            
            if (!is_numeric($item['id_producto'])) {
                
                $prod_db = $productoModel->obtenerPorSpotifyId($item['id_producto']);
                
                if ($prod_db) {
                    $id_producto_final = $prod_db['id_producto'];
                } else {
                    
                    try {
                        $album = $spotifyApi->obtenerAlbum($item['id_producto']);
                        if ($album && !isset($album['error'])) {
                            $datos_nuevos = [
                                'id_spotify' => $item['id_producto'],
                                'titulo' => $album['name'],
                                'artista' => $album['artists'][0]['name'],
                                'imagen_url' => $album['images'][0]['url'] ?? null,
                                'popularidad' => $album['popularity'] ?? 50,
                                'precio' => $item['precio'] 
                            ];
                            $id_nuevo = $productoModel->crear($datos_nuevos);
                            if ($id_nuevo) {
                                $id_producto_final = $id_nuevo;
                            }
                        }
                    } catch (\Exception $e) {
                        
                         $datos_minimos = [
                            'id_spotify' => $item['id_producto'],
                            'titulo' => $item['titulo'],
                            'artista' => 'Desconocido',
                            'imagen_url' => null,
                            'popularidad' => 50,
                            'precio' => $item['precio']
                        ];
                        $id_nuevo = $productoModel->crear($datos_minimos);
                         if ($id_nuevo) {
                            $id_producto_final = $id_nuevo;
                        }
                    }
                }
            }

            
            $item['id_producto'] = $id_producto_final;
            $carrito_procesado[] = $item;
            $total += $item['precio'] * $item['cantidad'];
        }

        $modelo = new Pedido();
        $id_pedido = $modelo->crearPedido($_SESSION['usuario_id'], $carrito_procesado, $total);

        if ($id_pedido) {
            unset($_SESSION['carrito']);
            
            header("Location: index.php?p=ticket&id=$id_pedido");
        } else {
            echo "Error al procesar el pedido.";
        }
    }

    public function historial() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?p=login');
            exit;
        }
        $modelo = new Pedido();
        $pedidos = $modelo->obtenerPorUsuario($_SESSION['usuario_id']);
        require_once '../app/views/historial.php';
    }

    public function generarTicketHTML() {
        $id_pedido = $_GET['id'];
        $modelo = new Pedido();
        $pedido = $modelo->obtenerPedido($id_pedido);
        $detalles = $modelo->obtenerDetalles($id_pedido);
        
        $productos_ticket = [];
        
        foreach ($detalles as $detalle) {
            $productos_ticket[] = [
                'titulo' => $detalle['titulo'],
                'artista' => $detalle['artista'],
                'imagen' => $detalle['imagen_url'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio_unitario'],
                'subtotal' => $detalle['cantidad'] * $detalle['precio_unitario']
            ];
        }
        
        require_once '../app/views/ticket.php';
    }
}
