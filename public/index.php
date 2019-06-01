<?php

require __DIR__  . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

$app = (new App\Init())->getApp();

$app->run();