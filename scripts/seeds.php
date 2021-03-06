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
  if( $result->num_rows != 3 ) {
    create_and_seed_offers( $log, $link );
    create_users( $log, $link );
    create_subscriptions( $log, $link );
  }
}

function create_and_seed_offers( $log, $link ) {
  $log->addInfo( "Creating table offers..." );
  $offers = "CREATE TABLE IF NOT EXISTS `offers` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `amount` int(11) NOT NULL default -1,
    `paymill_id` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`) )
    ENGINE=MyISAM  DEFAULT CHARSET=utf8";
  mysqli_query( $link, $offers );
  $log->addInfo( "Table offers successfully created" );

  $log->addInfo( "Seeding offers..." );
  mysqli_query( $link, "INSERT INTO offers ( name, amount, paymill_id ) VALUES ( 'Never Been Kissed', 500, 'offer_aae1832ae93c6ec79763' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, amount, paymill_id ) VALUES ( 'Can\'t Get Enought', 1200, 'offer_f2a81826ccd0e282e36f' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, amount, paymill_id ) VALUES ( 'Pure Bliss', 4900, 'offer_4b75aa84668420b20ade' );" );
  mysqli_query( $link, "INSERT INTO offers ( name, amount, paymill_id ) VALUES ( 'I\'m in Haven', 9900, 'offer_5a5cd3505e726778cbf1' );" );
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

  $log->addInfo( "Table users successfully created" );
}

function create_subscriptions( $log, $link ) {
  $log->addInfo( "Creating table subscriptions..." );
  $users = "CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id` int(11) unsigned NOT NULL auto_increment,
    `active` boolean,
    `next_capture_at` int(11) NOT NULL default -1,
    `canceled_at` int(11) default NULL,
    `paymill_id` varchar(255) NOT NULL default '',
    `payment_id` varchar(255) NOT NULL default '',
    `user_id` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`id`), FOREIGN KEY (user_id) REFERENCES users( id ) )
    ENGINE=MyISAM  DEFAULT CHARSET=utf8";
  mysqli_query( $link, $users );

  $log->addInfo( "Table users successfully created" );
}
