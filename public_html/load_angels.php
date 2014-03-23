<?php
require "../includes/init.php";


$items = $db->getArray("SELECT angel_id, name, bio, price FROM angels WHERE visible");

$angels = array();
foreach($items as $i) {
	$angels[$i['angel_id']] = $i;
}


header('Content-Type: application/json');
echo json_encode($angels);


?>