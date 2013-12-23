<?php

namespace LlamaKisses\Models;

abstract class Base {
  var $db;

  public function __construct() {
    $this->db = mysqli_connect( "localhost", "root", "root" );
    mysqli_select_db( $this->db, 'llama-kisses' );
  }
}
