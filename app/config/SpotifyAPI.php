<?php
namespace App\Config;

class SpotifyAPI
{
    private $cliente_id;
    private $cliente_secreto;
    private $token_acceso;

    public function __construct()
    {
        $this->cliente_id = $_ENV['SPOTIFY_CLIENT_ID'] ?? '';
        $this->cliente_secreto = $_ENV['SPOTIFY_CLIENT_SECRET'] ?? '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['spotify_token']) && isset($_SESSION['spotify_token_expira']) && time() < $_SESSION['spotify_token_expira']) {
            $this->token_acceso = $_SESSION['spotify_token'];
        } else {
            $this->autenticar();
        }
    }

    public function autenticar()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->cliente_id . ':' . $this->cliente_secreto),
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $resultado = curl_exec($ch);
        curl_close($ch);

        $datos = json_decode($resultado, true);

        if (isset($datos['access_token'])) {
            $this->token_acceso = $datos['access_token'];
            $_SESSION['spotify_token'] = $this->token_acceso;
            $_SESSION['spotify_token_expira'] = time() + $datos['expires_in'] - 60; 
            return true;
        }

        return false;
    }

    private function hacerPeticion($endpoint, $params = [])
    {
        if (!$this->token_acceso)
            $this->autenticar();

        $url = "https://api.spotify.com/v1/" . $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token_acceso
        ]);

        $resultado = curl_exec($ch);
        curl_close($ch);

        return json_decode($resultado, true);
    }

    public function obtenerNuevosLanzamientos($limite = 20)
    {
        return $this->hacerPeticion('browse/new-releases', [
            'limit' => $limite,
            'country' => 'ES'
        ]);
    }

    public function buscarAlbumes($query, $limite = 20)
    {
        return $this->hacerPeticion('search', [
            'q' => $query,
            'type' => 'album',
            'limit' => $limite
        ]);
    }

    public function obtenerAlbum($id)
    {
        return $this->hacerPeticion("albums/$id");
    }

    public function obtenerPlaylistItems($playlist_id, $limit = 50)
    {
        return $this->hacerPeticion("playlists/$playlist_id/tracks", [
            'limit' => $limit,
            'fields' => 'items(track(album(id,name,images,artists,release_date,total_tracks,external_urls)))'
        ]);
    }

    public function obtenerRecomendaciones($seed_artists, $limit = 20) {
        return $this->hacerPeticion('recommendations', [
            'seed_artists' => $seed_artists,
            'limit' => $limit,
            'min_popularity' => 20
        ]);
    }

    public function obtenerArtistasRelacionados($artist_id) {
        return $this->hacerPeticion("artists/$artist_id/related-artists");
    }

    public function obtenerTopTracksArtista($artist_id) {
        
        return $this->hacerPeticion("artists/$artist_id/top-tracks", ['market' => 'ES']);
    }

    public function obtenerArtista($artist_id) {
        return $this->hacerPeticion("artists/$artist_id");
    }
}
