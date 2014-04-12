<?php

?>



<!doctype html>
<html>
	<head>
		<title>Admin - Änglabud</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="/admin/admin.css" type="text/css" />
	</head>
	<body>
	
	
		<ul id="orderFilters">
			<li data-action="all">Alla</li>
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
		
		<script src="/admin/Orders.js"></script>
		<script src="/admin/admin.js"></script>
		
		
		
	</body>
</html>
		
