<?php

namespace LlamaKisses\Models;

abstract class Base {
  var $db;

  public function __construct() {
    $this->db = mysqli_connect( "llama-kisses.mysql.eu1.frbit.com", "llama-kisses", "FeqXTVLQXHtLWmx6" );
    mysqli_select_db( $this->db, 'llama-kisses' );
  }
}
