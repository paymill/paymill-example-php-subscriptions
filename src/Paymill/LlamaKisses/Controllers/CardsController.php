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

    // In live mode you should always validate the credit card
    // For more details please check https://support.paymill.com/hc/en-us/articles/200261507-How-can-I-store-credit-card-data-without-charging-a-transaction-right-away-
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
