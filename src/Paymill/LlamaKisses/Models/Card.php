<?php

namespace LlamaKisses\Models;

use Monolog\Logger;
use Paymill\Models\Request\Payment;
use Paymill\Models\Request\Preauthorization;

class Card extends Base {

  private $payment;

  private $type;
  private $cardType;
  private $lastFour;
  private $expirationDate;
  private $cardHolder;
  private $paymillId;

  public function __construct( $params = null ) {
    parent::__construct();
    $this->log = new Logger( 'LLAMA_KISSES::Card' );
    if( $params != null ) {
      $user = User::findById( $_SESSION['current_user'] );
      $this->payment = new Payment();
      $this->payment->setToken( $params['token'] )
                    ->setClient( $user->getPaymillId() );
    }
  }

  public function create() {
    $this->paymillId = $this->request->create( $this->payment )->getId();
  }

  public function verify( $amount, $currency ) {
    $preAuth = new Preauthorization();
    $preAuth->setPayment( $this->paymillId )
            ->setAmount( $amount )
            ->setCurrency( $currency );

    $response = $this->request->create( $preAuth );

    $preAuth = new Preauthorization();
    $preAuth->setId( $response->getId() );
    $this->request->delete( $preAuth );
  }

  public function destroy() {
    $payment = new Payment();
    $payment->setId( $this->paymillId );

    $this->request->delete( $payment );
  }

  public function getType() {
    return $this->type;
  }

  public function setType( $type ) {
    $this->type = $type;
  }

  public function getCardType() {
    return $this->cardType;
  }

  public function setCardType( $cardType ) {
    $this->cardType = $cardType;
  }

  public function getLastFour() {
    return $this->lastFour;
  }

  public function setLastFour( $lastFour ) {
    $this->lastFour = $lastFour;
  }

  public function getExpirationDate() {
    return $this->expirationDate;
  }

  public function setExpirationDate( $expirationDate ) {
    $this->expirationDate = $expirationDate;
  }

  public function getCardHolder() {
    return $this->cardHolder;
  }

  public function setCardHolder( $cardHolder ) {
    $this->cardHolder = $cardHolder;
  }

  public function getPaymillId() {
    return $this->paymillId;
  }

  public function setPaymillId( $paymillId ) {
    $this->paymillId = $paymillId;
  }

}
