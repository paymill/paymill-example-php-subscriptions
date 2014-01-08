<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;

abstract class ApplicationController {

  private    $action;
  private    $twig;
  private    $log;


  protected  $urlvalues;

  public function __construct( $action, $urlvalues, $twig ) {
    $this->action = $action;
    $this->urlvalues = $urlvalues;
    $this->twig = $twig;
    $this->log = new Logger( 'LLAMA_KISSES::ApplicationController' );
  }

  public function ExecuteAction() {
    return $this->{$this->action}();
  }

  protected function ReturnView( $model = array() ) {
    $this->log->addInfo( "Current user: " . $_SESSION['current_user'] );

    $model['current_user'] = $_SESSION['current_user'];
    $yield = $this->twig->render( $_GET['controller'].'/'.$this->action.'.html', $model );

    $template = $this->twig->loadTemplate( 'layouts/application.html' );
    if( array_key_exists( 'name', $model ) ) {
      echo $template->render( array( 'yield' => $yield, 'current_user' => $_SESSION['current_user'], 'current_user_name' => $model['name'] ) );
    } else {
      echo $template->render( array( 'yield' => $yield, 'current_user' => $_SESSION['current_user'] ) );
    }
  }
}
