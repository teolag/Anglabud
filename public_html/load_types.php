<?php
require "../includes/init.php";


$items = $db->getArray("SELECT type_id, type, pos, price FROM types WHERE visible=true ORDER BY `pos`");

$types = array();
foreach($items as $i) {
	$types[$i['type_id']] = $i;
}


header('Content-Type: application/json');
echo json_encode($types);


?>