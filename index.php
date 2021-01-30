<?php
require 'vendor/autoload.php';
use Slim\App as SlimApp;

$config =[
    "settings" =>[
        "displayErrorsDetails" => true
    ]
];
require_once "src/config.php";

$app = new SlimApp($config);

require_once "src/routes/routes.php";

$app->run();
