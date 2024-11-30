<?php
$observers = array(
	array(
        'eventname' => '\core\event\user_loggedout',
        'includefile' => '/local/ssosync/datasync.php',
        'callback' => 'wordpresslogout',
        'internal' => true)

);