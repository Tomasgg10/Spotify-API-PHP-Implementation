<?php

namespace Controllers;

require_once "src/services/SpotifyService.php";
require "Artists.php";

use \Services\SpotifyService;
use \Controllers\Artists;
use DateTime;

class Albums {
    private $spotify;
    private $artists;

    function __construct () {
        $this->spotify = new SpotifyService();
        $this->artists = new Artists();
    }

    public function findByArtist (
        string $artistName
    ) {
        if (!$artistName) {
            throw new \Exception("Band Name or Artist can not be empty");
        }
        $artists = $this->artists->findByName($artistName);
        $result = [];
        foreach ($artists as $artist) {
            $result = array_merge($result, $this->spotify->getAlbumsByArtist($artist['id'])->items);
        }
        return $result;
    }

    public function formatResponse (array $albums) {
        return json_encode(array_map(function ($album) {
            $date = new DateTime($album->release_date);
            return [
                "name" => $album->name,
                "released" => $date->format("d-m-Y"),
                "tracks" => $album->total_tracks,
                "cover" => $album->images[0]
            ];
        }, $albums));
    }
}