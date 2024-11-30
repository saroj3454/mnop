<?php
$observers = array(
	array(
        'eventname' => '\core\event\user_created',
        'includefile' => '/local/ecommerce/localevent.php',
        'callback' => 'userroleassigne',
        'internal' => true)
)
?>