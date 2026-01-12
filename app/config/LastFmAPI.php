<?php
namespace App\Config;

class LastFmAPI {
    
    private $api_key = 'f7bf1515c6e87b59a64c4a9995d601bb'; 
    private $base_url = 'http://ws.audioscrobbler.com/2.0/';

    public function __construct($api_key = null) {
        if ($api_key) {
            $this->api_key = $api_key;
        }
    }

    public function obtenerAlbumesSimilares($artista, $album, $limit = 5) {
        $params = [
            'method' => 'album.getsimilar',
            'artist' => $artista,
            'album' => $album,
            'api_key' => $this->api_key,
            'format' => 'json',
            'limit' => $limit,
            'autocorrect' => 1
        ];
        
        $url = $this->base_url . '?' . http_build_query($params);
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AlbumesMusicaApp/1.0'); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode != 200) {
            return null;
        }
        
        return json_decode($response, true);
    }

    public function obtenerArtistasSimilares($artista, $limit = 8) {
        $params = [
            'method' => 'artist.getsimilar',
            'artist' => $artista,
            'api_key' => $this->api_key,
            'format' => 'json',
            'limit' => $limit,
            'autocorrect' => 1
        ];
        
        $url = $this->base_url . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AlbumesMusicaApp/1.0');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode != 200) {
            return null;
        }
        
        return json_decode($response, true);
    }

    public function obtenerTopAlbumesPorTag($tag, $limit = 12) {
        $params = [
            'method' => 'tag.gettopalbums',
            'tag' => $tag,
            'api_key' => $this->api_key,
            'format' => 'json',
            'limit' => $limit,
            'autocorrect' => 1
        ];
        
        $url = $this->base_url . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AlbumesMusicaApp/1.0');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode != 200) {
            return null;
        }
        
        return json_decode($response, true);
    }
}
