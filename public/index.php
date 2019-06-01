<?php

require __DIR__  . '/../vendor/autoload.php';

$app = (new App\Init())->getApp();

$app->run();