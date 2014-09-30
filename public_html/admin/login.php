<?php
session_start();

if($_POST['password'] === "koko") {
	$_SESSION['admin'] = "inne";
}

if(!empty($_SESSION['admin'])) {
	header("Location: /admin");
	exit;
}

?>





<!doctype html>
<html>
	<head>
		<title>Änglabud Admin - Login</title>
		<meta charset="utf-8" />
		
	</head>
	<body>
	
		<form action="/admin/login.php" method="post">
			<input type="password" name="password" />
			<button type="submit">Go</button>
		</form>
		
	</body>
</html>