<?php

namespace LlamaKisses\Controllers;

class UsersController extends ApplicationController {

  protected function init() {
    $this->ReturnView( array( 'offer' => $_GET['offer_id'] ) );
  }

  protected function login() {
    $_SESSION['current_user'] = 1;
    $this->ReturnView();
  }

  protected function create() {
    $_SESSION['current_user'] = 1;
    $this->ReturnView();
  }

  protected function logout() {
    $_SESSION['current_user'] = null;
    $this->ReturnView();
  }

}
