<?php
$observers = array(

	array(
        'eventname' => '\core\event\user_loggedout',
        'includefile' => '/local/oawa_auth/eventsync.php',
        'callback' => 'wordpresslogout',
        'internal' => true),
	array(
        'eventname' => '\core\event\user_updated',
        'includefile' => '/local/oawa_auth/eventsync.php',
        'callback' => 'user_updated',
        'internal' => true),
        array(
        'eventname' => '\core\event\user_password_updated',
        'includefile' => '/local/oawa_auth/eventsync.php',
        'callback' => 'user_password_updated',
        'internal' => true)



);