<?php
// Get the POST data
$postData = file_get_contents("php://input");
file_put_contents("test.txt", date("Y-m-d H:i:s") . "    " . $postData, FILE_APPEND);


require '../includes/init.php';




// Set up API
$credentials = new PaysonCredentials($config['payson']['agentID'], $config['payson']['md5Key']);

$api = new PaysonApi($credentials, $test);

// Validate the request
$response = $api->validate($postData);

if ($response->isVerified()) {
    // IPN request is verified with Payson
    // Check details to find out what happened with the payment
    $details = $response->getPaymentDetails();
	
	$orderId = $details->getTrackingId();
	$token = $details->getToken();
	$paysonRef = $details->getPurchaseId();
	$paysonFee = $details->getReceiverFee();

    // After we have checked that the response validated we have to check the actual status 
    // of the transfer
    if ($details->getType() == "TRANSFER" && $details->getStatus() == "COMPLETED") {
        $db->update("UPDATE orders SET payed='Ja', payson_ref=?, payson_fee=? WHERE order_nr=?", array($paysonRef, $paysonFee, $orderId));
		$db->insert("INSERT INTO statuses(order_nr, message_id, payson_token) VALUES(?,2,?)", array($orderId, $token));
		
		
    } elseif ($details->getType() == "INVOICE" && $details->getStatus() == "PENDING" && $details->getInvoiceStatus() == "ORDERCREATED") {
        // Handle accepted invoice purchases here
    } else if ($details->getStatus() == "ERROR") {
        $db->insert("INSERT INTO statuses(order_nr, message_id, payson_token) VALUES(?,6,?)", array($orderId, $token));
    }
    /*
      //More info
      $response->getPaymentDetails()->getCustom();
      $response->getPaymentDetails()->getShippingAddressName();
      $response->getPaymentDetails()->getShippingAddressStreetAddress();
      $response->getPaymentDetails()->getShippingAddressPostalCode();
      $response->getPaymentDetails()->getShippingAddressCity();
      $response->getPaymentDetails()->getShippingAddressCountry();
      $response->getPaymentDetails()->getToken();
      $response->getPaymentDetails()->getType();
      $response->getPaymentDetails()->getStatus();
      $response->getPaymentDetails()->getCurrencyCode();
      $response->getPaymentDetails()->getTrackingId();
      $response->getPaymentDetails()->getCorrelationId();
      $response->getPaymentDetails()->getPurchaseId();
      $response->getPaymentDetails()->getSenderEmail();
      $response->getPaymentDetails()->getInvoiceStatus();
      $response->getPaymentDetails()->getGuaranteeStatus();
      $details->getReceiverFee();
     */
}
?>