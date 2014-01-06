<?php

namespace LlamaKisses\Models;

class User extends Base {

  private $id;
  private $email;
  private $password;
  private $name;
  private $paymillId;
  private $errors;

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
    } else {
      $user->errors['email'] = "Email or password did not match";
    }
    return $user;
  }

  public function __construct( $params = null ) {
    parent::__construct();
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

      $result = mysqli_query( $this->db, "SELECT id FROM `users` u WHERE u.email LIKE '$this->email'" );
      if( mysqli_num_rows( $result ) > 0 ) {
        $this->errors['email'] = "Email already taken";
      }
    }
  }

  public function create() {
    mysqli_query( $this->db, "INSERT INTO `users`( `email`, `password`, `name` ) VALUES( '$this->email', '$this->password', '$this->name' )" );
    $this->id = $this->db->insert_id;
    mysqli_close( $this->db );
  }

  public function getId() {
    return $this->id;
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
    $params['errors'] = $this->errors;
    return $params;
  }

}