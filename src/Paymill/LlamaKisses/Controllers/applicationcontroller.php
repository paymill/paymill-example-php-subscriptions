<?php

namespace LlamaKisses\Controllers;

abstract class ApplicationController {

  private    $action;
  private    $twig;

  protected  $urlvalues;

  public function __construct( $action, $urlvalues, $twig ) {
    $this->action = $action;
    $this->urlvalues = $urlvalues;
    $this->twig = $twig;
  }

  public function ExecuteAction() {
    return $this->{$this->action}();
  }

  protected function ReturnView( $model = array() ) {
    $yield = $this->twig->render( $_GET['controller'].'/'.$this->action.'.html', $model );
    $template = $this->twig->loadTemplate( 'layouts/application.html' );
    var_dump( $_SESSION['current_user'] );
    echo $template->render( array( 'yield' => $yield, 'current_user' => $_SESSION['current_user'] ) );
  }
}
