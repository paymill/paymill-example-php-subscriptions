<?php

namespace LlamaKisses\Models;

use Paymill\Request;

abstract class Base {

  protected $db;
  protected $request;

  public function __construct() {
    $this->db = mysqli_connect( "127.0.0.1", "root", "root", "llama-kisses" );
    $this->request = new Request( "97d4da6541622b4aa8fdde764c817646" );
  }

}
