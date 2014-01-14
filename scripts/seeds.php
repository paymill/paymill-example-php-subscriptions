<?php

function create_database( $log ) {
  $link = mysqli_connect( "127.0.0.1", "root", "root" );
  if( !mysqli_select_db( $link, 'llama-kisses' ) ) {
    $log->addInfo( "Creating database..." );
    mysqli_query( $link, "CREATE SCHEMA `llama-kisses`" );
    mysqli_select_db( $link, 'llama-kisses' );
  }
  return $link;
}

function seed_database( $log, $link ) {
  $result = mysqli_query( $link, "SHOW TABLES FROM `llama-kisses`" );
  if( $result->num_rows != 2 ) {
    create_and_seed_offers( $log, $link );
    create_users( $log, $link );
  }

}

function create_and_seed_offers( $log, $link ) {
  $log->addInfo( "Creating table offers..." );
  $offers = "CREATE TABLE IF NOT EXISTS `offers` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `paymill_id` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`) )
    ENGINE=MyISAM  DEFAULT CHARSET=utf8";
  mysqli_query( $link, $offers );

  $log->addInfo( "Seeding offers..." );
  mysqli_query( $link, "INSERT INTO offers ( name, paymill_id ) VALUES ( 'Never Been Kissed', 'offer_be422103d62502b5b4df' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, paymill_id ) VALUES ( 'Can\'t Get Enought', 'offer_bccb3afdac32b123e8ed' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, paymill_id ) VALUES ( 'Pure Bliss', 'offer_27079a90961d34708f14' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, paymill_id ) VALUES ( 'I\'m in Haven', 'offer_6bb2de2da980d39d7566' );" );

  $log->addInfo( "Offers successfully created" );
}

function create_users( $log, $link ) {
  $log->addInfo( "Creating table users..." );
  $users = "CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `email` varchar(255) NOT NULL default '' UNIQUE,
    `password` varchar(255) NOT NULL default '',
    `name` varchar(255) NOT NULL default '',
    `paymill_id` varchar(255) NOT NULL default '',
    `offer_id` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`), FOREIGN KEY (offer_id) REFERENCES offers( id ) )
    ENGINE=MyISAM  DEFAULT CHARSET=utf8";
  mysqli_query( $link, $users );

  $log->addInfo( "Users successfully created" );
}
