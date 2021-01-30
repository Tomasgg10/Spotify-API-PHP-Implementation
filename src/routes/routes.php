<?php

require "src/controllers/Albums.php";

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use \Controllers\Albums;

$app->get('/api/v1/albums', function (ServerRequestInterface $request, ResponseInterface $response) {
    $params = $request->getQueryParams();
    $artist = $params["q"] ?? "";
    $albumsController = new Albums();
    try {
        $albums = $albumsController->findByArtist($artist);
        $albumsFormated = $albumsController->formatResponse($albums);
        $response->getBody()->write($albumsFormated);
    } catch (Exception $e) {
        $response->getBody()->write($e->getMessage());
    }
    return $response;
});

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write("URL not valid");
    return $response;
});
