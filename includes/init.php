<?php

define("ROOT", $_SERVER['DOCUMENT_ROOT']."/../");


session_start();
require ROOT . "includes/config.php";
require "/git/DatabasePDO/DatabasePDO.php";
require "/git/PaysonAPI/lib/paysonapi.php";


$db = new DatabasePDO($config['db']['server'],$config['db']['username'],$config['db']['password'],$config['db']['name']);


if($config['payson']['testAPI']) {
	$config['payson']['agentID'] = "4";
	$config['payson']['md5Key'] = "2acab30d-fe50-426f-90d7-8c60a7eb31d4";
	$config['payson']['receiverEmail'] = "testagent-1@payson.se";
}




function dec2angel($dec) {
	$md = md5($dec);
	return strtoupper(substr($md,0,2) . base_convert($dec, 10, 35) . substr($md,-2));
}

function angel2dec($angel) {
	$dec = base_convert(substr($angel,2,-2), 35, 10);
	$md = strtoupper(md5($dec));
	if(substr($md,0,2)==substr($angel,0,2) && substr($md,-2)==substr($angel,-2)) return $dec;
	else return 0;
}

function generatePassword() {
	$noOfChars = 8;
	return substr(md5(time()."lkutf"),0,$noOfChars);
}



?>
