<?php

namespace LlamaKisses\ClassLoader;

use Monolog\Logger;

class ApplicationLoader {

  private $controller;
  private $action;
  private $urlvalues;

  private $log;

  public function __construct( $urlvalues ) {
    $this->log = new Logger( 'LLAMA_KISSES::ApplicationLoader' );
    $this->log->addInfo( 'Try to execute controller: '.$urlvalues['controller'].' with action: '.$urlvalues['action'] );
    $this->urlvalues = $urlvalues;
    if( $this->urlvalues['controller'] == "" ) {
      $this->controller = 'LlamaKisses\Controllers\PagesController';
    } else {
      $this->controller = 'LlamaKisses\Controllers\\'.ucwords( $this->urlvalues['controller'] ).'Controller';
    }
    if( $this->urlvalues['action'] == "" ) {
      $this->action = "index";
    } else {
      $this->action = $this->urlvalues['action'];
    }
  }

  public function CreateController($twig) {
    if (class_exists($this->controller)) {
      $parents = class_parents($this->controller);
      if (in_array("LlamaKisses\Controllers\ApplicationController", $parents)) {
        if (method_exists($this->controller,$this->action)) {
          return new $this->controller($this->action, $this->urlvalues, $twig);
        }
      }
    }
    return "Can not find Controller: ".$this->controller." with action: ".$this->action;
  }
}