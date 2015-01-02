<?php
session_start();
if(empty($_SESSION['admin'])) {
	die("Access denied");
}

require "../../includes/init.php";


$sql = "SELECT order_nr,
	buyer_firstname,
	buyer_lastname,
	(SELECT message_id FROM statuses AS s WHERE s.order_nr = o.order_nr ORDER BY date DESC LIMIT 1) AS last_message_id,
	(SELECT date FROM statuses AS s WHERE s.order_nr = o.order_nr ORDER BY date DESC LIMIT 1) AS last_date
FROM orders AS o
ORDER BY last_message_id DESC,
	last_date DESC
";

$sql = "SELECT * FROM orders AS o JOIN statuses AS s USING(order_nr) ORDER BY s.date DESC";

$rows = $db->getArray($sql);
$orders = array();


foreach($rows as $r) {

	if(is_array($orders[$r['order_nr']])) {
		$order = $orders[$r['order_nr']];
	} else {
		$order = array();
	}

	$order['order_id'] = dec2angel($r['order_nr']);
	$order['first_name'] = $r['buyer_firstname'];
	$order['last_name'] = $r['buyer_lastname'];
	$order['email'] = $r['buyer_email'];
	$order['payed'] = ($r['payed']==="Ja")? true : false;


	if(!is_array($order['statuses'])) {
		$order['statuses'] = array();
	}
	$order['statuses'][] = array(
		"date"=>$r['date'],
		"message_id"=>$r['message_id']
	);

	$orders[$r['order_nr']] = $order;
}

header('Content-type: application/json');
echo json_encode($orders);
?>


