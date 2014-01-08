<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use LlamaKisses\Models\Card;

class CardsController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::CardsController' );
  }

  protected function create() {
    $card = new Card( $_POST['card'] );
    $card->create();
    $this->ReturnView();
  }

  protected function destroy() {
    $card = new Card();
    $card->setPaymillId( $_GET['payment_id'] );
    $card->destroy();
    $this->ReturnView();
  }
}
