<?php

require '../includes/init.php';



$description = "Änglabud.se";




if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$angelPrice = $db->getValue("SELECT price FROM angels WHERE angel_id=?", array($_POST['angel']['id']));
	$addon = $db->getRow("SELECT type, price FROM types WHERE type_id=?", array($_POST['angel']['type_id']));
	$amountToReceive = $angelPrice + $addon['price'];
	
	$order = array(
		"angel_id" => $_POST['angel']['id'],
		"type_id" => $_POST['angel']['type_id'],
		"buyer_firstname" => $_POST['sender']['first_name'],
		"buyer_lastname" => $_POST['sender']['last_name'],
		"buyer_email" => $_POST['sender']['email'],
		"buyer_password" => generatePassword(),
		"buyer_phone" => $_POST['sender']['phone'],
		"buyer_ip" => $_SERVER['REMOTE_ADDR'],
		"deliver_firstname" => $_POST['reciever']['first_name'],
		"deliver_lastname" => $_POST['reciever']['last_name'],
		"deliver_street" => $_POST['reciever']['street'],
		"deliver_zipcode" => $_POST['reciever']['zip_code'],
		"deliver_city" => $_POST['reciever']['city'],
		"deliver_country" => $_POST['reciever']['country'],
		"message" => $_POST['delivery']['message'],
		"senddate" => $_POST['delivery']['date'],
		"package" => 'bag',
		"comments" => $_POST['delivery']['other'],
		"found" => $_POST['sender']['found'],
		"cost" => $amountToReceive
	);
	$orderNr = $db->insertArray("orders", $order);
	$db->insert("INSERT INTO statuses(order_nr, message_id) VALUES(?,1)", array($orderNr));
	echo "new order: " . $orderNr;
} elseif(isset($_GET['orderId'])) {
	$orderNr = angel2dec($_GET['orderId']);
	$order = $db->getRow("SELECT * FROM orders WHERE order_nr=?", array($orderNr));
	if(empty($order)) {
		die("No order with id: " . $orderId);
	} elseif($order['payed']=="Ja") {
		die("This order is already payed");
	}
	$angelPrice = $db->getValue("SELECT price FROM angels WHERE angel_id=?", array($order['angel_id']));
	$addon = $db->getRow("SELECT type, price FROM types WHERE type_id=?", array($order['type_id']));
	$amountToReceive = $angelPrice + $addon['price'];
}


$orderItems = array();
$orderItems[] = new OrderItem("Ängel", $angelPrice*0.8, 1, 0.25, "a".$_POST['angel']['id']);
$orderItems[] = new OrderItem($addon['type'], $addon['price']*0.8, 1, 0.25, "t".$_POST['angel']['type_id']);



$senderFirstname = $order['buyer_firstname'];
$senderLastname = $order['buyer_lastname'];
$senderEmail = $order['buyer_email'];

$angelId = $order['angel_id'];
$angelType = $order['type_id'];



$credentials = new PaysonCredentials($config['payson']['agentID'], $config['payson']['md5Key']);
$api = new PaysonApi($credentials, $config['payson']['testAPI']);


$receiver = new Receiver($config['payson']['receiverEmail'], $amountToReceive);
$receivers = array($receiver);

$sender = new Sender($senderEmail, $senderFirstname, $senderLastname);
$payData = new PayData($config['payson']['returnURL'], $config['payson']['cancelURL'], $config['payson']['ipnURL'], $description, $sender, $receivers);
$payData->setOrderItems($orderItems);
$payData->setFundingConstraints(array(FundingConstraint::BANK, FundingConstraint::CREDITCARD));
$payData->setFeesPayer(FeesPayer::PRIMARYRECEIVER);
$payData->setCurrencyCode(CurrencyCode::SEK);
$payData->setLocaleCode(LocaleCode::SWEDISH);
//$payData->setLocaleCode(LocaleCode::ENGLISH);
$payData->setGuaranteeOffered(GuaranteeOffered::OPTIONAL);
//$payData->setGuaranteeOffered(GuaranteeOffered::NO);
$payData->setShowReceiptPage(false);
$payData->setTrackingId(dec2angel($orderNr));

$payResponse = $api->pay($payData);
if ($payResponse->getResponseEnvelope()->wasSuccessful()) {
    header("Location: " . $api->getForwardPayUrl($payResponse));
} else {
	echo "nehepp...";
	var_dump($payResponse);
}
?>