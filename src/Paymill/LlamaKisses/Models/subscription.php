<?php

namespace LlamaKisses\Models;

use Monolog\Logger;

class Subscription extends Base {

  private $client;
  private $offer;
  private $payment;
  private $paymillId;

  public function __construct( $params = null ) {
    parent::__construct();
    if( $params != null ) {
      $user = User::findById( $_SESSION['current_user'] );
      $this->client = $user->getPaymillId();
      $this->offer = Offer::findById( $user->getOfferId() )->getPaymillId();
      $this->payment = $params;
    }
  }

  public function create() {
    $subscription = new \Paymill\Models\Request\Subscription();
    $subscription->setClient( $this->client )
                 ->setOffer( $this->offer )
                 ->setPayment( $this->payment );

    $response = $this->request->create( $subscription );
    $this->paymillId = $response->getId();
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
