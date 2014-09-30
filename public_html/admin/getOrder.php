<?php
require "../../includes/init.php";




$sql = "SELECT * FROM orders WHERE order_id=?";


$order = $db->getArray($sql, $_GET['id']);
$response = array();

$response['order'] = $order;


header('Content-type: application/json');
echo json_encode($response);
?>


