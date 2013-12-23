<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use LlamaKisses\Models\User;

class UsersController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::UsersController' );
  }

  protected function init() {
    $this->ReturnView( array( 'offer' => $_GET['offer_id'] ) );
  }

  protected function login() {
    $_SESSION['current_user'] = 1;
    $this->ReturnView();
  }

  protected function create() {
    $user = new User( $_POST['user'] );
    if( $user->getErrors() != null ) {
      $_SESSION['current_user'] = null;
    } else {
      $_SESSION['current_user'] = $user->getId();
    }
    $this->ReturnView( $user->toArray() );
  }

  protected function logout() {
    $_SESSION['current_user'] = null;
    $this->ReturnView();
  }

}
