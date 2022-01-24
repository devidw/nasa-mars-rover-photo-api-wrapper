<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Devidw\MarsRoverPhoto\MarsRoverPhoto;

$mars = new MarsRoverPhoto(
    apiKey: 'DEMO_KEY',
    rover: 'curiosity',
);

$photos = $mars->sol(100)->camera('NAVCAM')->get();

var_dump($photos);
