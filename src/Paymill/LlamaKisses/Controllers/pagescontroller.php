<?php

namespace LlamaKisses\Controllers;

use LlamaKisses\Models\User;

class PagesController extends ApplicationController {

  protected function index() {
    $user = User::findById( $_SESSION['current_user'] );
    $this->ReturnView( $user->toArray() );
  }

}
