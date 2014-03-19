<?php

namespace LlamaKisses\Models;

class Offer extends Base {

  private $id;
  private $name;
  private $amount;
  private $paymillId;

  public static function findById( $id ) {
    $offer = new Offer();
    $result = mysqli_query( $offer->db, "SELECT * FROM `offers` o WHERE o.id LIKE '$id'" );
    if( mysqli_num_rows( $result ) == 1 ) {
        $row = mysqli_fetch_array( $result, MYSQLI_ASSOC );
        $offer->id = $row['id'];
        $offer->name = $row['name'];
        $offer->amount = $row['amount'];
        $offer->paymillId = $row['paymill_id'];
    }
    return $offer;
  }

  public function __construct( $params = null ) {
    parent::__construct();
  }

  public function getPaymillId() {
    return $this->paymillId;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function getName() {
    return $this->name;
  }

}
