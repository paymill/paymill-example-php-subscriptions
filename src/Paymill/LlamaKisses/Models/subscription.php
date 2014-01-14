<?php

namespace LlamaKisses\Models;

use Monolog\Logger;

class Subscription extends Base {

  private $client;
  private $offer;
  private $payment;
  private $paymillId;

  public function __construct( $paymentId = null ) {
    parent::__construct();
    if( $paymentId != null ) {
      $user = User::findById( $_SESSION['current_user'] );
      $this->client = $user->getPaymillId();
      $this->offer = Offer::findById( $user->getOfferId() )->getPaymillId();
      $this->payment = $paymentId;
    }
  }

  public function create() {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setFilter( array( "offer" => $this->offer ) );
    $response = $this->request->getAll( $subscription );

    // if( $response != null ) {
    //   foreach( $response as $sub ) {
    //     if( $sub['client']['id'] == $this->client ) {
    //       $subscription = new \Paymill\Models\Request\Subscription();
    //       $subscription->setId( $sub['id'] )
    //                    ->setOffer( $this->offer )
    //                    ->setPayment( $this->payment );
    //     $this->request->update( $subscription );
    //     return;
    //     }
    //   }
    // }
    $subscription->setClient( $this->client )
                 ->setOffer( $this->offer )
                 ->setCancelAtPeriodEnd( true )
                 ->setPayment( $this->payment );

    $response = $this->request->create( $subscription );
    $this->paymillId = $response->getId();
  }

  public function update( $subscription_id ) {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setId( $subscription_id )
                 ->setPayment( $this->payment );
    $this->request->update( $subscription );
  }

  public function delete( $subscription_id ) {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setId( $subscription_id );
    $this->request->delete( $subscription );
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
