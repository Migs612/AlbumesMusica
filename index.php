<?php
session_start();


spl_autoload_register(function ($clase) {
    
    $prefijo = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $longitud = strlen($prefijo);
    if (strncmp($prefijo, $clase, $longitud) !== 0) {
        return;
    }

    $clase_relativa = substr($clase, $longitud);

    $partes = explode('\\', $clase_relativa);
    $nombre_archivo = array_pop($partes);
    $directorios = array_map('strtolower', $partes);
    
    $archivo = $base_dir . implode('/', $directorios) . '/' . $nombre_archivo . '.php';

    if (file_exists($archivo)) {
        require $archivo;
    }
});

use App\Controllers\ControladorCatalogo;
use App\Controllers\ControladorAutenticacion;
use App\Controllers\ControladorPedido;
use App\Controllers\ControladorFavorito;


$pagina = isset($_GET['p']) ? $_GET['p'] : 'home';

switch ($pagina) {
    case 'home':
        $controlador = new ControladorCatalogo();
        $controlador->index();
        break;
    
    case 'catalogo':
        $controlador = new ControladorCatalogo();
        $controlador->catalogo();
        break;

    case 'detalle':
        $controlador = new ControladorCatalogo();
        $controlador->detalle();
        break;

    case 'favoritos':
        $controlador = new ControladorFavorito();
        $controlador->index();
        break;

    case 'toggle_favorito':
        $controlador = new ControladorFavorito();
        $controlador->toggle();
        break;

    case 'login':
        $controlador = new ControladorAutenticacion();
        $controlador->login();
        break;

    case 'registro':
        $controlador = new ControladorAutenticacion();
        $controlador->registro();
        break;

    case 'logout':
        $controlador = new ControladorAutenticacion();
        $controlador->logout();
        break;

    case 'carrito':
        $controlador = new ControladorPedido();
        $controlador->verCarrito();
        break;

    case 'agregar_carrito':
        $controlador = new ControladorPedido();
        $controlador->agregarCarrito();
        break;

    case 'actualizar_carrito':
        $controlador = new ControladorPedido();
        $controlador->actualizarCarrito();
        break;

    case 'eliminar_carrito':
        $controlador = new ControladorPedido();
        $controlador->eliminarDelCarrito();
        break;

    case 'procesar_pedido':
        $controlador = new ControladorPedido();
        $controlador->procesarPedido();
        break;

    case 'historial':
        $controlador = new ControladorPedido();
        $controlador->historial();
        break;

    case 'ticket':
        $controlador = new ControladorPedido();
        $controlador->generarTicketHTML();
        break;

    default:
        echo "Página no encontrada";
        break;
}
