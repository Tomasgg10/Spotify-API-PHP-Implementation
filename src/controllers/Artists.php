<?php

namespace Controllers;

require_once "src/services/SpotifyService.php";

use \Services\SpotifyService;

class Artists {
    private $spotify;

    function __construct () {
        $this->spotify = new SpotifyService();
    }

    public function findByName (string $artist) {
        if (!$artist) {
            throw new \Exception("Band Name or Artist can not be empty");
        }

        $apiResponse = $this->spotify->getArtists($artist);
        return $this->formatResponse($apiResponse);
    }

    private function formatResponse ($apiResponse) {
        $artists = json_decode($apiResponse);
        return array_map(function ($artist) {
            return ["id" => $artist->id, "name" => $artist->name];
        }, $artists->artists->items);
    }
}
