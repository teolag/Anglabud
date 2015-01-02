<?php
session_start();
if(empty($_SESSION['admin'])) {
	die("Access denied");
}

require "../../includes/init.php";




$sql = "INSERT INTO statuses(order_nr, message_id) VALUES(?, 4)";


$order = $db->getRow($sql, array(angel2dec($_GET['id'])));
$response = array();

$response['message'] = "Order has been set to done";


header('Content-type: application/json');
echo json_encode($response);
?>


