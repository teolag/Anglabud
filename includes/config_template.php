<?php
$config = array(
	"salt" => "",
	"db" => array(
		"server" => "",
		"username" => "",
		"password" => "",
		"name" => ""
	),
	"payson" => array(
		"testAPI" => true/false,
		"agentID" => "",
		"md5Key" => "",
		"receiverEmail" => "",
		"returnURL" => "http://domain.se/receipt.php",
		"cancelURL" => "http://domain.se/cancel.php",
		"ipnURL" => "http://domain.se/payson_ipn.php"
	)	
);
?>