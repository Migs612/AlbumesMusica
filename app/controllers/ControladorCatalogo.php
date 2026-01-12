<?php
namespace App\Controllers;

use App\Models\Producto;
use App\Models\Favorito;
use App\Config\SpotifyAPI;
use App\Config\LastFmAPI;

class ControladorCatalogo
{

    private $api;

    public function __construct()
    {
        $this->api = new SpotifyAPI();
    }

    
    private function normalizarDatosSpotify($albumes_spotify)
    {
        $productos = [];
        foreach ($albumes_spotify as $album) {
            
            
            $hash = crc32($album['id']);
            $popularidad = 50 + ($hash % 46); 

            $productos[] = [
                'id_producto' => $album['id'], 
                'titulo' => $album['name'],
                'artista' => $album['artists'][0]['name'],
                'imagen_url' => isset($album['images'][0]['url']) ? $album['images'][0]['url'] : null,
                'precio' => Producto::calcularPrecioInicial($popularidad),
                'popularidad' => $popularidad,
                'es_spotify' => true 
            ];
        }
        return $productos;
    }

    public function index()
    {
        
        
        
        $datos = $this->api->obtenerPlaylistItems('37i9dQZEVXbMDoHDwVN2tF', 50);
        $productos_destacados = [];

        if (isset($datos['items'])) {
            $albumes_raw = [];
            $ids_vistos = [];
            
            foreach ($datos['items'] as $item) {
                if (isset($item['track']['album'])) {
                    $album = $item['track']['album'];
                    
                    if (!in_array($album['id'], $ids_vistos)) {
                        $ids_vistos[] = $album['id'];
                        $albumes_raw[] = $album;
                    }
                }
                if (count($albumes_raw) >= 8) break; 
            }
            
            $productos_destacados = $this->normalizarDatosSpotify($albumes_raw);
        } else {
            
            $modelo = new Producto();
            $productos_destacados = array_slice($modelo->obtenerTodos(), 0, 4);
        }

        require_once 'app/views/home.php';
    }

    public function catalogo()
    {
        $genero = isset($_GET['genero']) ? $_GET['genero'] : null;
        $busqueda = isset($_GET['q']) ? trim($_GET['q']) : null;
        $productos = [];

        
        $mapa_generos = [
            1 => 'rock',
            2 => 'pop',
            3 => 'jazz',
            4 => 'latino',
            5 => 'hip-hop',
            6 => 'electronic',
            7 => 'indie',
            8 => 'metal',
            9 => 'r-n-b',
            10 => 'classical'
        ];

        if ($busqueda) {
            
            $datos = $this->api->buscarAlbumes($busqueda, 20);
            
            if (isset($datos['albums']['items'])) {
                $productos = $this->normalizarDatosSpotify($datos['albums']['items']);
            }

        } elseif ($genero && isset($mapa_generos[$genero])) {
            
            
            $tag = $mapa_generos[$genero];
            
            $lastfm = new LastFmAPI();
            $top_albums = $lastfm->obtenerTopAlbumesPorTag($tag, 12); 
            
            if (isset($top_albums['albums']['album'])) {
                foreach ($top_albums['albums']['album'] as $album_fm) {
                    
                    $nombre = $album_fm['name'];
                    $artista = $album_fm['artist']['name'];
                    
                    
                    $q = 'album:"' . str_replace('"', '', $nombre) . '" artist:"' . str_replace('"', '', $artista) . '"';
                    $res = $this->api->buscarAlbumes($q, 1);
                    
                    if (isset($res['albums']['items'][0])) {
                        $spotify_album = $res['albums']['items'][0];
                        
                        
                        $hash = crc32($spotify_album['id']);
                        $popularidad = 50 + ($hash % 46);

                        $productos[] = [
                            'id_producto' => $spotify_album['id'],
                            'titulo' => $spotify_album['name'],
                            'artista' => $spotify_album['artists'][0]['name'],
                            'imagen_url' => isset($spotify_album['images'][0]['url']) ? $spotify_album['images'][0]['url'] : null,
                            'precio' => Producto::calcularPrecioInicial($popularidad),
                            'popularidad' => $popularidad,
                            'es_spotify' => true
                        ];
                    }
                }
            }
            
            
            if (empty($productos)) {
                $query = 'genre:"' . $mapa_generos[$genero] . '"';
                $datos = $this->api->buscarAlbumes($query, 20);
                if (isset($datos['albums']['items'])) {
                    $productos = $this->normalizarDatosSpotify($datos['albums']['items']);
                }
            }
            
        } else {
            
            
            $query = 'year:2024';
            $datos = $this->api->buscarAlbumes($query, 20);
            
            if (isset($datos['albums']['items'])) {
                $productos = $this->normalizarDatosSpotify($datos['albums']['items']);
            }
        }

        
        if (empty($productos)) {
            
            $modelo = new Producto();
            $productos = $modelo->obtenerTodos();
        }

        require_once 'app/views/catalogo.php';
    }

    public function detalle()
    {
        if (!isset($_GET['id'])) {
            header('Location: index.php?p=catalogo');
            exit;
        }
        $id = $_GET['id'];

        $modelo = new Producto();
        $producto = null;
        $tracks = [];

        
        if (is_numeric($id)) {
            $producto = $modelo->obtenerPorId($id);
        } else {
            $producto = $modelo->obtenerPorSpotifyId($id);
        }

        
        if (!$producto) {
            $datos_album = $this->api->obtenerAlbum($id);

            if (isset($datos_album['id'])) {
                
                $hash = crc32($datos_album['id']);
                $popularidad = 50 + ($hash % 46);

                $nuevo_producto = [
                    'titulo' => $datos_album['name'],
                    'artista' => $datos_album['artists'][0]['name'],
                    'imagen_url' => $datos_album['images'][0]['url'],
                    'popularidad' => $popularidad,
                    'precio' => Producto::calcularPrecioInicial($popularidad),
                    'id_spotify' => $datos_album['id']
                ];

                $id_interno = $modelo->crear($nuevo_producto);
                $producto = $modelo->obtenerPorId($id_interno);

                if (isset($datos_album['tracks']['items'])) {
                    $tracks = $datos_album['tracks']['items'];
                }
            }
        } else {
            
            if (!empty($producto['id_spotify'])) {
                $datos_album = $this->api->obtenerAlbum($producto['id_spotify']);
                if (isset($datos_album['tracks']['items'])) {
                    $tracks = $datos_album['tracks']['items'];
                }
            }
        }

        if (!$producto) {
            echo "Producto no encontrado";
            exit;
        }

        
        
        
        
        
        $albumes_similares = [];
        
        $lastfm = new LastFmAPI(); 
        $similares_data = $lastfm->obtenerArtistasSimilares($producto['artista'], 8);

        if (isset($similares_data['similarartists']['artist'])) {
            $artistas_similares = $similares_data['similarartists']['artist'];
            
            
            if (isset($artistas_similares['name'])) {
                $artistas_similares = [$artistas_similares];
            }

            $ids_vistos = [];

            foreach ($artistas_similares as $artista_fm) {
                if (count($albumes_similares) >= 4) break; 

                $nombre_artista = $artista_fm['name'];
                
                
                
                $query = 'artist:"' . str_replace('"', '', $nombre_artista) . '"';
                $search_results = $this->api->buscarAlbumes($query, 1);

                if (isset($search_results['albums']['items'][0])) {
                    $album = $search_results['albums']['items'][0];
                    
                    
                    
                    if (isset($producto['id_spotify']) && $album['id'] == $producto['id_spotify']) continue;
                    
                    
                    if (in_array($album['id'], $ids_vistos)) continue;
                    $ids_vistos[] = $album['id'];

                    $albumes_similares[] = [
                        'id_producto' => $album['id'],
                        'titulo' => $album['name'],
                        'imagen_url' => isset($album['images'][0]['url']) ? $album['images'][0]['url'] : null,
                        'artista' => $album['artists'][0]['name']
                    ];
                }
            }
        }

        
        
        
        if (count($albumes_similares) == 0) {
             $artist_id = null;
            if (isset($datos_album['artists'][0]['id'])) {
                $artist_id = $datos_album['artists'][0]['id'];
            } elseif (!empty($producto['id_spotify'])) {
                 $d = $this->api->obtenerAlbum($producto['id_spotify']);
                 if(isset($d['artists'][0]['id'])) $artist_id = $d['artists'][0]['id'];
            }

            if ($artist_id) {
                $related = $this->api->obtenerArtistasRelacionados($artist_id);
                if (isset($related['artists'])) {
                    foreach ($related['artists'] as $rel_artist) {
                        if (count($albumes_similares) >= 4) break;
                        
                        $top = $this->api->obtenerTopTracksArtista($rel_artist['id']);
                        if (isset($top['tracks'][0]['album'])) {
                            $album = $top['tracks'][0]['album'];
                            $albumes_similares[] = [
                                'id_producto' => $album['id'],
                                'titulo' => $album['name'],
                                'imagen_url' => isset($album['images'][0]['url']) ? $album['images'][0]['url'] : null,
                                'artista' => $rel_artist['name']
                            ];
                        }
                    }
                }
            }
        }

        $es_favorito = false;
        if (isset($_SESSION['usuario_id'])) {
            $modeloFavorito = new Favorito();
            $es_favorito = $modeloFavorito->esFavorito($_SESSION['usuario_id'], $producto['id_producto']);
        }

        require_once 'app/views/detalle.php';
    }
}
