<?php
session_start();
if(empty($_SESSION['admin'])) {
	die("Access denied");
}

require "../../includes/init.php";




$sql = "SELECT * FROM orders JOIN angels USING(angel_id) JOIN types USING(type_id) WHERE order_nr=?";
$order = $db->getRow($sql, array(angel2dec($_GET['id'])));

$sql = "SELECT date, message, message_id FROM statuses JOIN messages USING(message_id) WHERE order_nr = ? ORDER BY date";
$statuses = $db->getArray($sql, array(angel2dec($_GET['id'])));

$response = array();

$response['order'] = $order;
$response['statuses'] = $statuses;


header('Content-type: application/json');
echo json_encode($response, JSON_NUMERIC_CHECK);
?>


