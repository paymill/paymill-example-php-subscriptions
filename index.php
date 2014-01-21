<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'scripts/seeds.php';

use Monolog\Logger;
use LlamaKisses\ClassLoader\ApplicationLoader;

$log = new Logger( 'LLAMA_KISSES::MAIN' );

$link = create_database( $log );
seed_database( $log, $link );
mysqli_close( $link );

$twigLoader = new Twig_Loader_Filesystem( 'src/Paymill/LlamaKisses/Views/' );
$twig = new Twig_Environment( $twigLoader, array( 'cache' => 'cache', 'debug' => true ) );

//create the controller and execute the action
$appLoader = new ApplicationLoader( $_GET );
$controller = $appLoader->CreateController( $twig );
if( is_string( $controller ) == false ) {
  $controller->ExecuteAction();
} else {
  echo $controller;
}
