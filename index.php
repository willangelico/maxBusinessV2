<?php

namespace MaxBusiness;

require "vendor/autoload.php"; 

use MaxFW\Config\Define;
use MaxFW\Functions\Helpers;
use MaxFW\Functions\Router;


$define = new Define();
$helpers = new Helpers();
$router = new Router($helpers);

// var_dump($router->parameters);
// echo date_default_timezone_get();
// echo NAME;
// $testeClass = new teste();
// $testeClass->testar();
// echo "teste";