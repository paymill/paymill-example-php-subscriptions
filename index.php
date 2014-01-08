<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'scripts/seeds.php';

include 'src/Paymill/LlamaKisses/ClassLoader/applicationloader.php';

include 'src/Paymill/LlamaKisses/Controllers/applicationcontroller.php';
include 'src/Paymill/LlamaKisses/Controllers/cardscontroller.php';
include 'src/Paymill/LlamaKisses/Controllers/pagescontroller.php';
include 'src/Paymill/LlamaKisses/Controllers/subscriptionscontroller.php';
include 'src/Paymill/LlamaKisses/Controllers/userscontroller.php';

include 'src/Paymill/LlamaKisses/Models/base.php';
include 'src/Paymill/LlamaKisses/Models/card.php';
include 'src/Paymill/LlamaKisses/Models/offer.php';
include 'src/Paymill/LlamaKisses/Models/subscription.php';
include 'src/Paymill/LlamaKisses/Models/user.php';

use Monolog\Logger;
use LlamaKisses\ClassLoader\ApplicationLoader;

$log = new Logger( 'LLAMA_KISSES::MAIN' );

$link = create_database( $log );
seed_database( $log, $link );
mysqli_close( $link );

$twigLoader = new Twig_Loader_Filesystem( 'src/Paymill/LlamaKisses/Views/' );
$twig = new Twig_Environment( $twigLoader, array( 'cache' => 'cache', 'debug' => true ) );

//create the controller and execute the action
$appLoader = new ApplicationLoader($_GET);
$controller = $appLoader->CreateController($twig);
$controller->ExecuteAction();

