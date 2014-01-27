<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LlamaKisses\Models\Subscription;

class SubscriptionsController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::SubscriptionsController' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );
  }

  protected function create() {
    $subscription = new Subscription( $_GET['payment_id'] );
    $subscription->create();
    $this->ReturnView();
  }

  protected function update() {
    $subscription = new Subscription( $_GET['payment_id'] );
    $subscription->update( $_GET['subscription_id'] );
    $this->ReturnView();
  }

  protected function delete() {
    $subscription = new Subscription( $_GET['payment_id'] );
    $subscription->delete( $_GET['subscription_id'] );
    $this->ReturnView();
  }

}