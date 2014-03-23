var Orders = (function(){
	var orders, ordersToPrint, table,
	messages={
		1:"Beställning lagd, inväntar betalning",
		2:"Betalning mottagen, bearbetar beställning",
		3:"Beställningen färdig för leverans, inväntar angivet datum",
		4:"Beställningen skickad",
		5:"Beställningen uppdaterad",
		6:"Betalning misslyckad"
	},
	orderFilters,


	init = function() {
		console.log("init orders");
		table = document.getElementById("tableOrders");
		orderFilters = document.getElementById("orderFilters");
		orderFilters.addEventListener("click", filterAction, false);
	},
	
	filterAction = function(e) {
		var action = e.target.dataset.action;
		console.log("filter action", action);
		
		ordersToPrint = Object.keys(orders);
		switch(action) {
			case "todo":
			ordersToPrint = ordersToPrint.filter(function(orderId){
				var order = orders[orderId];
				if(order.statuses[0].message_id!=2 && order.statuses[0].message_id!=3) return false;
				return true;
			});		
			break;
		
			case "incomplete":
			ordersToPrint = ordersToPrint.filter(function(orderId){
				var order = orders[orderId];
				if(order.statuses[0].message_id!=1 && order.statuses[0].message_id!=5 && order.statuses[0].message_id!=6) return false;
				return true;
			});		
			break;
			
			case "done":
			ordersToPrint = ordersToPrint.filter(function(orderId){
				var order = orders[orderId];
				if(order.statuses[0].message_id!=4) return false;
				return true;
			});		
			break;
		
			default:	
		}
		printOrders();
		
	},


	loadAll = function() {
		var xhr = new XMLHttpRequest();

		xhr.open("get", "/admin/getAllOrders.php", true);
		xhr.responseType="json";
		xhr.onload = loadAllCallback;
		xhr.send();
	},

	loadAllCallback = function(e) {
		console.log("orders loaded", e);
		orders = e.target.response;
		ordersToPrint = Object.keys(orders);
		ordersToPrint = ordersToPrint.filter(filterOrders);
		printOrders();		
	},
	
	filterOrders = function(orderId) {
		var order = orders[orderId];
		
		//if(order.statuses[0].message_id!=2) return false;
		
		//if(!order.payed) return false;
		
		//if(orderId>50) return false;
		
		return true;
	},



	printOrders = function() {		
		table.innerHTML="";
		for(var i=0; i<ordersToPrint.length; i++) {
			var order = orders[ordersToPrint[i]];
			
			console.log(order);
			
			
			var tsCreated, tsLatest=order.statuses[0].date, latestMessageId=order.statuses[0].message_id;
			for(var j=0; j<order.statuses.length; j++) {
				var status = order.statuses[j];
				if(status.message_id==="1") {
					tsCreated = status.date;
				}
			}
			
			var tr = document.createElement("TR");
			
			var td = document.createElement("TD");
			td.textContent = ordersToPrint[i];
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = order.first_name;
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = order.last_name;
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = order.email;
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = order.payed;
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = tsCreated;
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = messages[latestMessageId];
			tr.appendChild(td);
			
			var td = document.createElement("TD");
			td.textContent = tsLatest;
			tr.appendChild(td);
			
			table.appendChild(tr);			
		}
	};
	
	
	
	
	return {
		init: init,
		loadAll: loadAll
	}


}());