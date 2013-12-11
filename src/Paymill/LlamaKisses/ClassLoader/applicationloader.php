<?php

namespace LlamaKisses\ClassLoader;

class ApplicationLoader {

  private $controller;
  private $action;
  private $urlvalues;

  //store the URL values on object creation
  public function __construct($urlvalues) {
    $this->urlvalues = $urlvalues;
    if ($this->urlvalues['controller'] == "") {
      $this->controller = 'LlamaKisses\Controllers\Pages\Controller';
    } else {
      $this->controller = 'LlamaKisses\Controllers\\'.ucwords($this->urlvalues['controller']).'Controller';
    }
    if ($this->urlvalues['action'] == "") {
      $this->action = "index";
    } else {
      $this->action = $this->urlvalues['action'];
    }
  }

  //establish the requested controller as an object
  public function CreateController($twig) {
    //does the class exist?
    if (class_exists($this->controller)) {
      $parents = class_parents($this->controller);
      //does the class extend the controller class?
      if (in_array("LlamaKisses\Controllers\ApplicationController", $parents)) {
        //does the class contain the requested method?
        if (method_exists($this->controller,$this->action)) {
          return new $this->controller($this->action, $this->urlvalues, $twig);
        } else {
          //bad method error
          return new Error("badUrl",$this->urlvalues);
        }
      } else {
        //bad controller error
        return new Error("badUrl",$this->urlvalues);
      }
    } else {
      //bad controller error
      return new Error("badUrl",$this->urlvalues);
    }
  }
}