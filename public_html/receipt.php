<?php
require '../includes/init.php';


// Fetch the token that are returned
$token = $_GET["TOKEN"];


// Initialize the API in test mode
$credentials = new PaysonCredentials($config['payson']['agentID'], $config['payson']['md5Key']);

$api = new PaysonApi($credentials, $config['payson']['testAPI']);

// Get the details about this purchase
$detailsResponse = $api->paymentDetails(new PaymentDetailsData($token));

// First we verify that the call to payson succeeded.
if ($detailsResponse->getResponseEnvelope()->wasSuccessful()) {

    // Get the payment details from the response
    $details = $detailsResponse->getPaymentDetails();
	$orderId = $details->getTrackingId();
	$paysonRef = $details->getPurchaseId();
	$paysonFee = $details->getReceiverFee();
  
	if ($details->getType() == "TRANSFER" && $details->getStatus() == "COMPLETED") {
        $db->update("UPDATE orders SET payed='Ja', payson_ref=?, payson_fee=? WHERE order_nr=?", array($paysonRef, $paysonFee, $orderId));
		$db->insert("INSERT INTO statuses(order_nr, message_id, payson_token) VALUES(?,2,?)", array($orderId, $token));
		$message = "Tack för din beställning";
    } elseif ($details->getType() == "INVOICE" && $details->getStatus() == "PENDING" && $details->getInvoiceStatus() == "ORDERCREATED") {
        // Handle accepted invoice purchases here
		$message = "???";
    } else if ($details->getStatus() == "ERROR") {
        $db->insert("INSERT INTO statuses(order_nr, message_id, payson_token) VALUES(?,6,?)", array($orderId, $token));
		$message = "Något gick fel, kontakta info@anglabud.se för mer information";
    }
	?>
	
	<?php require "header.php"; ?>
	<div class="box">
		<?php echo $message; ?>		
	</div>
	<?php require "footer.php"; ?>
	
	
	<?php
    /* Below are the other data that can be retreived from payment details
      $details->getCustom();
      $details->getShippingAddressName();
      $details->getShippingAddressStreetAddress();
      $details->getShippingAddressPostalCode();
      $details->getShippingAddressCity();
      $details->getShippingAddressCountry();
      $details->getToken();
      $details->getType();
      $details->getStatus();
      $details->getCurrencyCode();
      $details->getTrackingId();
      $details->getCorrelationId();
      $details->getPurchaseId();
      $details->getSenderEmail();
      $details->getInvoiceStatus();
      $details->getGuaranteeStatus();
      $details->getReceiverFee();
     * 
     */
} else {
    $detailsErrors = $detailsResponse->getResponseEnvelope()->getErrors();
    ?>
    <h3>Error</h3>
    <table>
        <tr>
            <th>
                Error id
            </th>

            <th>
                Message
            </th>

            <th>
                Parameter
            </th>
        </tr>
        <?php
        foreach ($detailsErrors as $error) {
            echo "<tr>";
            echo "<td>" . $error->getErrorId() . '</td>';
            echo "<td>" . $error->getMessage() . '</td>';
            echo "<td>" . $error->getParameter() . '</td>';
            echo "</tr>";
        }
        ?>
    </table>
    <?php
}
?>
