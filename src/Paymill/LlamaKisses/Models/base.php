<?php

namespace LlamaKisses\Models;

abstract class Base {
  protected $database;
  public function __construct() {
    // $this->database = new PDO("mysql:host=localhost;dbname=test", "username", "password");
  }
}
