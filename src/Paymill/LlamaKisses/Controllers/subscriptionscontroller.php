<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use LlamaKisses\Models\Subscription;

class SubscriptionsController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::SubscriptionsController' );
  }

  protected function create() {
    $subscription = new Subscription( $_GET['payment_id'] );
    $subscription->create();
    $this->ReturnView();
  }

  protected function update() {
    var_dump( $_GET );
  }

}