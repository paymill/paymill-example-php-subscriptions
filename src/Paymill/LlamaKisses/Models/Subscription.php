<?php

namespace LlamaKisses\Models;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Subscription extends Base {

  private $client;
  private $offer;
  private $payment;
  private $paymillId;

  private $log;

  public function __construct( $paymentId = null ) {
    parent::__construct();
    $this->log = new Logger( 'LLAMA_KISSES::Subscription' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );

    if( $paymentId != null ) {
      $user = User::findById( $_SESSION['current_user'] );
      $this->client = $user->getPaymillId();
      $this->offer = Offer::findById( $user->getOfferId() )->getPaymillId();
      $this->payment = $paymentId;
    }
  }

  public function create() {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setClient( $this->client )
                 ->setOffer( $this->offer )
                 ->setCancelAtPeriodEnd( true )
                 ->setPayment( $this->payment );
    $response = $this->request->create( $subscription );

    $this->paymillId = $response->getId();
    if( $this->paymillId != null ) {
      $sql =  "INSERT INTO `subscriptions`( `active`, `next_capture_at`, `canceled_at`, `paymill_id`, `payment_id`, `user_id` ) " .
              "VALUES( true, " . $response->getNextCaptureAt() . ", null, '$this->paymillId', '$this->payment', " . $_SESSION['current_user'] . " )";
      mysqli_query( $this->db, $sql );
    }
    mysqli_close( $this->db );
  }

  public function update( $subscription_id ) {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setId( $subscription_id )
                 ->setPayment( $this->payment );
    $this->request->update( $subscription );

    mysqli_query( $this->db, "UPDATE subscriptions s SET payment_id = '$this->payment' WHERE s.paymill_id LIKE '$subscription_id'" );
    mysqli_close( $this->db );
  }

  public function delete( $subscription_id ) {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setId( $subscription_id );
    $response = $this->request->delete( $subscription );

    mysqli_query($this->db, "UPDATE subscriptions s SET s.canceled_at = " . $response->getCanceledAt() . " WHERE s.paymill_id LIKE '$subscription_id'");
    mysqli_close($this->db);
  }

  public function setOffer( $offer ) {
    $this->offer = $offer;
  }

  public function getPayment() {
    return $this->payment;
  }

  public function setPayment( $payment ) {
    $this->payment = $payment;
  }

  public function setClient( $client ) {
    $this->client = $client;
  }

  public function getPaymillId() {
    return $this->paymillId;
  }

  public function setPaymillId( $paymillId ) {
    $this->paymillId = $paymillId;
  }

}
