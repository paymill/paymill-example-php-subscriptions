<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );
  }

  public function ExecuteAction() {
    return $this->{$this->action}();
  }

  protected function ReturnView( $model = array() ) {
    $current_user = $_SESSION['current_user'];
    $this->log->addInfo( "Current user: " . $current_user );
    $model['current_user'] = $current_user;

    $yield = null;
    if( isset( $_GET['controller'] ) == false )
      $yield = $this->twig->render( "pages".'/'.$this->action.'.html', $model );
    else
      $yield = $this->twig->render( strtolower( $_GET['controller'] ).'/'.$this->action.'.html', $model );

    $template = $this->twig->loadTemplate( 'layouts/application.html' );

    if( array_key_exists( 'name', $model ) ) {
      echo $template->render( array( 'yield' => $yield, 'current_user' => $current_user, 'current_user_name' => $model['name'] ) );
    } else {
      echo $template->render( array( 'yield' => $yield, 'current_user' => $current_user ) );
    }
  }
}
