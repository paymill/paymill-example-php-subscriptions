<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LlamaKisses\Models\User;

class UsersController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::UsersController' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );
  }

  protected function init() {
    $this->ReturnView( array( 'offer' => $_GET['offer_id'] ) );
  }

  protected function login() {
    $this->log->addInfo( 'Logging in as '.$_POST['user']['email'] );
    $user = User::findByCredentials( $_POST['user']['email'], $_POST['user']['password'] );
    $_SESSION['current_user'] = $user->getId();
    $this->ReturnView( $user->toArray() );
  }

  protected function create() {
    $user = new User( $_POST['user'] );
    if( $user->getErrors() == null ) {
      $user->create();
      $_SESSION['current_user'] = $user->getId();
    }
    $this->ReturnView( $user->toArray() );
  }

  protected function logout() {
    $_SESSION['current_user'] = null;
    $this->ReturnView();
  }

}
