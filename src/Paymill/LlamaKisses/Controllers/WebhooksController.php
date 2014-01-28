<?php

namespace LlamaKisses\Controllers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use LlamaKisses\Models\Subscription;

class WebhooksController extends ApplicationController {

  private $db;
  private $log;

  public function __construct( $action, $urlvalues, $twig ) {
    parent::__construct( $action, $urlvalues, $twig );
    $this->log = new Logger( 'LLAMA_KISSES::WebhooksController' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );

    $this->db = mysqli_connect( "127.0.0.1", "root", "root" );
    mysqli_select_db( $this->db, 'llama-kisses' );
  }

  public function receive() {
    $response = json_decode( @file_get_contents( "php://input" ), true );
    $type = $response['event']['event_type'];
    $resource = $response['event']['event_resource'];

    $this->log->addInfo( "PAYMILL event " . $type . " received" );

    switch( $type ) {
      case 'subscription.updated':
        $this->log->addInfo( "subscription.updated" );
        $subscriptionId = $resource['id'];
        $this->log->addInfo( "Subscription Id: '" . $subscriptionId . "'" );

        $select = "SELECT * FROM subscriptions s WHERE s.paymill_id LIKE '" . $subscriptionId . "'";
        $result = mysqli_query( $this->db, $select );
        if( mysqli_num_rows( $result ) == 1 ) {
          $row = mysqli_fetch_array( $result );
          $active = $row['active'];
          if( $active == false ) {
            // The customer has updated this payment method
            $update = "UPDATE subscriptions s SET active = true WHERE s.paymill_id LIKE '" . $subscriptionId . "'";
            mysqli_query( $this->db, $update );

            $transaction = new \Paymill\Models\Request\Transaction();
            $transaction->setAmount( $resource['offer']['amount'] )
                        ->setCurrency( 'EUR' )
                        ->setPayment( $resource['payment']['id'] )
                        ->setDescription( 'Subscription#' . $subscriptionId . ' ' . $resource['offer']['name'] );

            $request = new \Paymill\Request( "97d4da6541622b4aa8fdde764c817646" );
            $response = $request->create( $transaction );

            // Here you can notify (e.g. via email) the user that you ware able to charge him
          }
        }
        break;
      case 'transaction.failed':
        $this->log->addInfo( "Handling transaction.failed" );
        $subscriptionId = substr( $resource['description'], 13, 24 );
        $this->log->addInfo( "Subscription Id: '" . $subscriptionId . "'" );

        $select = "SELECT * FROM subscriptions s WHERE s.paymill_id LIKE '" . $subscriptionId . "'";
        $result = mysqli_query( $this->db, $select );
        if( mysqli_num_rows( $result ) == 1 ) {
          $row = mysqli_fetch_array( $result );
          $active = $row['active'];
          if( $active == true ) {
            // first time when the subscription fails to charge the customer
            $update = "UPDATE subscriptions s SET active = false WHERE s.paymill_id LIKE '" . $subscriptionId . "'";
            mysqli_query( $this->db, $update );
            // Here you can notify (e.g. via email) the user that you ware unable to charge him
          } else {
            // second time when the subscription fails to charge the customer
            // we will delete his subscription
            $subscription = new Subscription( $row['payment'] );
            $subscription->delete( $subscriptionId );
          }
        }
        mysqli_close( $this->db );
        break;
      default:
        $this->log->addInfo( "No action required" );
    }
  }

}
