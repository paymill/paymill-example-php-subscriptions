<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LlamaKisses\Models\Card;

class CardsController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::CardsController' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );
  }

  protected function create() {
    $card = new Card( $_POST['card'] );
    $card->create();
    $card->verify( $_POST['amount'], $_POST['currency'] );
    $this->ReturnView();
  }

  protected function destroy() {
    $card = new Card();
    $card->setPaymillId( $_GET['payment_id'] );
    $card->destroy();
    $this->ReturnView();
  }
}
