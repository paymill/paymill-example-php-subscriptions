<?php

namespace LlamaKisses\ClassLoader;

class ApplicationLoader {

  private $controller;
  private $action;
  private $urlvalues;

  public function __construct($urlvalues) {
    $this->urlvalues = $urlvalues;
    if ($this->urlvalues['controller'] == "") {
      $this->controller = 'LlamaKisses\Controllers\PagesController';
    } else {
      $this->controller = 'LlamaKisses\Controllers\\'.ucwords( $this->urlvalues['controller'] ).'Controller';
    }
    if ($this->urlvalues['action'] == "") {
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