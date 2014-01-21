<?php

namespace LlamaKisses\Controllers;

use LlamaKisses\Models\User;

class PagesController extends ApplicationController {

  protected function index() {
    $current_user = isset( $_SESSION['current_user'] ) ? $_SESSION['current_user'] : "";
    $user = User::findById( $current_user );
    $this->ReturnView( $user->toArray() );
  }

}
