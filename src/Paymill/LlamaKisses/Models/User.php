<?php

namespace LlamaKisses\Models;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Paymill\Models\Request\Client;

class User extends Base {

  private $id;
  private $email;
  private $password;
  private $name;
  private $paymillId;
  private $offerId;
  private $errors;
  private $cards;
  private $subscription;

  private $log;

  public static function findById( $id ) {
    $user = new User();
    $result = mysqli_query( $user->db, "SELECT * FROM `users` u WHERE u.id LIKE '$id'" );
    if( mysqli_num_rows( $result ) == 1 ) {
        $row = mysqli_fetch_array( $result );
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        $user->name = $row['name'];
        $user->paymillId = $row['paymill_id'];
        $user->offerId = $row['offer_id'];
        $user->findAllCards();
        $user->findSubscription();
    }
    return $user;
  }

  public static function findByCredentials( $email, $password ) {
    $user = new User();
    $result = mysqli_query( $user->db, "SELECT * FROM `users` u WHERE u.email LIKE '$email' AND u.password LIKE '$password'" );
    if( mysqli_num_rows( $result ) == 1 ) {
        $row = mysqli_fetch_array( $result );
        $user->id = $row['id'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        $user->name = $row['name'];
        $user->paymillId = $row['paymill_id'];
        $user->offerId = $row['offer_id'];
        $user->findAllCards();
        $user->findSubscription();
    } else {
      $user->errors['email'] = "Email or password did not match";
    }
    return $user;
  }

  public function __construct( $params = null ) {
    parent::__construct();
    $this->log = new Logger( 'LLAMA_KISSES::User' );
    $this->log->pushHandler( new StreamHandler( 'logs/llama_ranch.log', Logger::INFO ) );
    if( $params != null ) {
      if( $params['password'] !== $params['password_confirmation'] ) {
        $this->errors['password'] = "Password didn't match";
      }
      if( empty( $params['password' ] ) ) {
        $this->errors['password'] = "Password can't be blank";
      }
      if( empty( $params['email' ] ) ) {
        $this->errors['email'] = "Email can't be blank";
      }
      if( empty( $params['name' ] ) ) {
        $this->errors['name'] = "Name can't be blank";
      }
      $this->email = $params['email'];
      $this->password = $params['password'];
      $this->name = $params['name'];
      $this->offerId = $params['offer_id'];

      $result = mysqli_query( $this->db, "SELECT id FROM `users` u WHERE u.email LIKE '$this->email'" );
      if( mysqli_num_rows( $result ) > 0 ) {
        $this->errors['email'] = "Email already taken";
      }
    }
  }

  public function create() {
    $client = new \Paymill\Models\Request\Client();
    $client->setEmail( $this->email );
    $id = $this->request->create( $client )->getId();
    $this->log->addInfo( $id );

    mysqli_query( $this->db, "INSERT INTO `users`( `email`, `password`, `name`, `paymill_id`, `offer_id` ) VALUES( '$this->email', '$this->password', '$this->name', '$id', '$this->offerId' )" );
    $this->id = $this->db->insert_id;
    mysqli_close( $this->db );
  }

  public function getId() {
    return $this->id;
  }

  public function getOfferId() {
    return $this->offerId;
  }

  public function getCards() {
    return $this->cards;
  }

  public function getPaymillId() {
    return $this->paymillId;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function toArray() {
    $params = array();
    $params['id'] = $this->id;
    $params['email'] = $this->email;
    $params['name'] = $this->name;
    $params['paymill_id'] = $this->paymillId;
    $params['offer_id'] = $this->offerId;
    $params['cards'] = $this->cards;
    $params['subscription'] = $this->subscription;
    $params['errors'] = $this->errors;
    return $params;
  }

  private function findAllCards() {
    $client = new Client();
    $client->setId( $this->paymillId );
    $response = $this->request->getOne( $client );

    $this->cards = array();
    foreach( $response->getPayment() as $payment ) {
      $card = new Card();
      $card->setType( $payment->getType() );
      $card->setCardHolder( $payment->getCardHolder() );
      $card->setCardType( $payment->getCardType() );
      $card->setLastFour( $payment->getLastFour() );
      $card->setExpirationDate( $payment->getExpireMonth() . '/' . $payment->getExpireYear() );
      $card->setPaymillId( $payment->getId() );
      array_push( $this->cards, $card );
    }
  }

  private function findSubscription() {
    $result = mysqli_query( $this->db, "SELECT * FROM `subscriptions` s WHERE s.user_id = " . $this->id . " AND s.canceled_at IS NULL" );
    if( mysqli_num_rows( $result ) == 1 ) {
        $row = mysqli_fetch_array( $result );
        $this->subscription = new Subscription();
        $this->subscription->setOffer( $this->offerId );
        $this->subscription->setPayment( $row['payment_id'] );
        $this->subscription->setPaymillId( $row['paymill_id'] );
        $this->subscription->setActive( $row['active'] );
    }
  }

}
