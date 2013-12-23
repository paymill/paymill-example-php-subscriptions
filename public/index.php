<?php
session_start();

require_once __DIR__.'/../vendor/autoload.php';
include __DIR__.'/../scripts/seeds.php';

use Monolog\Logger;

$log = new Logger( 'LLAMA_KISSES::MAIN' );

$link = create_database( $log );
seed_database( $log, $link );
mysqli_close( $link );

use LlamaKisses\ClassLoader\ApplicationLoader;

$twigLoader = new Twig_Loader_Filesystem( '../src/Paymill/LlamaKisses/Views/' );
$twig = new Twig_Environment( $twigLoader, array( 'cache' => './cache', 'debug' => true ) );

//create the controller and execute the action
$appLoader = new ApplicationLoader($_GET);
$controller = $appLoader->CreateController($twig);
$controller->ExecuteAction();

