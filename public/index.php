<?php
require_once __DIR__.'/../vendor/autoload.php';

use LlamaKisses\ClassLoader\AppLoader;

$twigLoader = new Twig_Loader_Filesystem( '../src/Paymill/LlamaKisses/Views/' );
$twig = new Twig_Environment( $twigLoader, array( 'cache' => './cache', 'debug' => true ) );

//create the controller and execute the action
$appLoader = new AppLoader($_GET);
$controller = $appLoader->CreateController($twig);
$controller->ExecuteAction();

