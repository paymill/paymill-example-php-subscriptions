<?php

namespace LlamaKisses\Controllers;

abstract class BaseController {

  protected $urlvalues;
  protected $action;
  protected $twig;

  public function __construct($action, $urlvalues, $twig) {
    $this->action = $action;
    $this->urlvalues = $urlvalues;
    $this->twig = $twig;
  }

  public function ExecuteAction() {
    return $this->{$this->action}();
  }

  protected function ReturnView($viewmodel) {

    $classNames = explode( '\\', get_class( $this ) );
    $yield = $this->twig->render(strtolower( substr( array_pop( $classNames ), 0, -10 ) ).'/'.$this->action.'.html', array('names' => $viewmodel ));

    $template = $this->twig->loadTemplate('application.html');
    echo $template->render(array('yield' => $yield));

  }
}
