<?php

namespace Services;

use Exception;
use GuzzleHttp\Client;

Class SpotifyService {
    private $client;
    private $token;
    function __construct () {
        $this->client = new Client();
        $this->token = $this->getToken();
    }
    
    private function getToken() : string {
        try {
            $res = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
                'headers'  => [
                    'Authorization' => "Basic ".SPOTIFY_CREDENTIAL,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ]
            ]);
            $token = json_decode($res->getBody())->access_token;
            return $token;
        } catch (\Exception $e) {
           throw new Exception("Error while getting TOKEN:{$e->getMessage()}");
        }
    }

    public function getArtists(string $name) {
        try {
            $url = "https://api.spotify.com/v1/search?type=artist&q={$name}";
            $res = $this->client->request('GET', $url, [
                'headers'  => [
                    'authorization' => "Bearer {$this->token}",
                    'Content-Type' => 'application/json'
                ],
            ]);
            return $res->getBody();
        } catch (\Exception $e) {
           throw new Exception("Error Spotify Service: {$e->getMessage()}");
        }
    }

    public function getAlbumsByArtist(string $id) {
        try {
            $url = "https://api.spotify.com/v1/artists/{$id}/albums";
            $curl = curl_init();
            if ($curl === false) {
                throw new \Exception('failed to initialize');
            }
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer {$this->token}",
                    "Content-type: application/json; charset=UTF-8",
                    "Accept: application/json"
                ),
            ));
            $response = curl_exec($curl);
            return json_decode($response);
        } catch (\Exception $e) {
           throw new Exception("Error Spotify Service: {$e->getMessage()}");
        }
    }


}