<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;

class WebhooksController extends ApplicationController {

  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::WebhooksController' );
  }

  public function receive() {
    $response = json_decode( @file_get_contents( "php://input" ), true );
    $type = $response['event']['event_type'];
    $resource = $response['event']['event_resource'];

    $this->log->addInfo( "PAYMILL event " . $eventType . " received" );

    switch( $type ) {
      case 'subscription.deleted':
        $this->log->addInfo( "Handling subscription.deleted" );
        break;
      case 'transaction.failed':
        $this->log->addInfo( "Handling transaction.failed" );
        break;
      default:
        $this->log->addInfo( "No action required" );
    }
  }

}
