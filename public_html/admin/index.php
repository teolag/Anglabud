<?php

session_start();
if(empty($_SESSION['admin'])) {
	header("Location: /admin/login.php");
}

?>



<!doctype html>
<html>
	<head>
		<title>Admin - Änglabud</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="/admin/admin.css" type="text/css" />
		<link rel="stylesheet" href="http://xio.se/projects/xiopop/XioPop.css" type="text/css" />
	</head>
	<body>
		<a href="/admin/logout.php">Logout</a>

		<ul id="orderFilters">
			<li data-action="">Alla</li>
			<li data-action="todo">Att göra</li>
			<li data-action="incomplete">Ofullständiga</li>
			<li data-action="done">Skickade</li>
		</ul>

		<table id="tableOrders">
			<thead>
				<tr>
					<th>Nr</th>
					<th>Id</th>
					<th>Förnamn</th>
					<th>Efternamn</th>
					<th>Epost</th>
					<th>Betalat</th>
					<th>Beställningsdatum</th>
					<th>Status</th>
					<th>Senast status</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>

		<template id="tplOrder">
			<h1></h1>
			<div class="buyer">
				<h3>Köpare</h3>
				<div class="name"></div>
				<div class="email"></div>
				<div class="phone"></div>
			</div>

			<div class="angel">
				<img />
				<h3>Ängel</h3>
				<div class="name"></div>
				<div class="type"></div>
				<div class="price"></div>
			</div>

			<div class="reciever">
				<h3>Mottagare</h3>
				<div class="name"></div>
				<div class="street"></div>
				<div class="postcode"></div>
				<div class="country"></div>
			</div>

			<div class="message">
				<h3>Meddelande</h3>
				<div class="text"></div>
			</div>

			<div class="extra">
				<h3>Hur hittade du hit?</h3>
				<div class="found"></div>
			</div>

			<ul class="orderLogg"></ul>
			<button class="btnOrderDone" type="button">
				Ängeln är skickad
			</button>

		</template>


		<script src="/admin/Orders.js"></script>
		<script src="http://xio.se/projects/xiopop/XioPop.js"></script>
		<script src="http://xio.se/AjaXIO/AjaXIO.js"></script>
		<script src="/admin/admin.js"></script>



	</body>
</html>

