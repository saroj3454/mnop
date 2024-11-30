<?php
require_once('../../config.php');
$t=time();
echo $t;
echo "<br>";
echo "<br>";
$daystart = usergetmidnight($t);

print_r($daystart);