<?php

use floor12\catastro\ClientCatastro;

include 'vendor/autoload.php';

$client = new ClientCatastro('4607201XH9240N0001AH');
$inmueble = $client->getInmueble();
$client->saveStreetViewPhoto('example.jpg');

var_dump($inmueble);