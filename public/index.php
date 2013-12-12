<?php
require_once __DIR__.'/../vendor/autoload.php';

$twigLoader = new Twig_Loader_Filesystem( '../src/Paymill/LlamaKisses/Views/' );
$twig = new Twig_Environment( $twigLoader, array( 'cache' => './cache', 'debug' => true ) );

//create the controller and execute the action
$appLoader = new \LlamaKisses\ClassLoader\ApplicationLoader($_GET);
$controller = $appLoader->CreateController($twig);
$controller->ExecuteAction();
