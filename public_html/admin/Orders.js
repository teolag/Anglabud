var Orders = (function(){
	var orders, ordersToPrint, tableBody,
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
		tableBody = document.querySelector("#tableOrders tbody");
		tableBody.addEventListener("click", tableClickHandler, false);

		orderFilters = document.getElementById("orderFilters");
		orderFilters.addEventListener("click", filterAction, false);
	},

	filterAction = function(e) {
		var action = e.target.dataset.action;
		console.log("filter action", action);
		window.location.hash = action;

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
		ordersToPrint = ordersToPrint.sort(sortOrders);
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
		ordersToPrint = ordersToPrint.sort(sortOrders);
		printOrders();
	},

	filterOrders = function(orderId) {
		var order = orders[orderId];

		//if(order.statuses[0].message_id!=2) return false;

		//if(!order.payed) return false;

		//if(orderId>50) return false;

		return true;
	},

	sortOrders = function(id1, id2) {
		var o1 = orders[id1];
		var o2 = orders[id2];

		var ts1 = new Date(o1.statuses[0].date);
		var ts2 = new Date(o2.statuses[0].date);

		return ts2 - ts1;
	},



	printOrders = function() {
		tableBody.innerHTML="";
		for(var i=0; i<ordersToPrint.length; i++) {
			var order = orders[ordersToPrint[i]];

			//console.log(order);


			var tsCreated, tsLatest=order.statuses[0].date, latestMessageId=order.statuses[0].message_id;
			for(var j=0; j<order.statuses.length; j++) {
				var status = order.statuses[j];
				if(status.message_id==="1") {
					tsCreated = status.date;
				}
			}

			var tr = document.createElement("TR");
			tr.dataset.id = order.order_id;

			var td = document.createElement("TD");
			td.textContent = ordersToPrint[i];
			tr.appendChild(td);

			var td = document.createElement("TD");
			td.textContent = order.order_id;
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

			tableBody.appendChild(tr);
		}
	},

	tableClickHandler = function(e) {
		console.log("click");

		var target = e.target;
		while(true) {
			switch(true) {
				case target.nodeName==="TR":
					showOrder(target.dataset.id);
				break;
				case target.nodeName==="TABLE":
					return;
				break;
			}
			target = target.parentElement;
		}

		/*
		användaren klickar någonstans i tabellen
		klickat på en tr ---> bra!
		klickat någonstans under tr ---> kolla parent
		klickat utanför tr ---> bryt
		*/

		while(tr.nodeName!=="TABLE") {
			if(tr.nodeName==="TR") break;
		}

	},

	showOrder = function(orderId) {
		console.log("open order:", orderId);
		var div = document.createElement("DIV");
		div.classList.add("order");
		div.textContent = "Loading order: " + orderId;
		XioPop.showElement(div);


		Ajax.getJSON("/admin/getOrder.php", {id:orderId}, function(json) {
			var o = json.order;
			var t = document.getElementById('tplOrder').content;
			t.querySelector("h1").textContent = 'Order: ' + orderId;
			t.querySelector(".buyer .name").textContent = o.buyer_firstname + ' ' + o.buyer_lastname;
			t.querySelector(".buyer .email").textContent = o.buyer_email;
			t.querySelector(".buyer .phone").textContent = o.buyer_phone;

			t.querySelector(".angel img").src = "/img/angels200/"+o.angel_id+".jpg";
			t.querySelector(".angel .name").textContent = o.name;
			t.querySelector(".angel .type").textContent = o.type;
			t.querySelector(".angel .price").textContent = o.cost + " kr";

			t.querySelector(".reciever .name").textContent = o.deliver_firstname + ' ' + o.deliver_lastname;
			t.querySelector(".reciever .street").textContent = o.deliver_street;
			t.querySelector(".reciever .postcode").textContent = o.deliver_zipcode + ' ' + o.deliver_city;
			t.querySelector(".reciever .country").textContent = o.deliver_country;

			t.querySelector(".message .text").innerHTML = o.message.replace(/\n/g,"<br>");

			t.querySelector(".extra .found").innerHTML = o.found;

			var orderLogg = t.querySelector(".orderLogg");
			orderLogg.innerHTML = "";
			var lastStatusId = null;
			for(var i=0; i<json.statuses.length; i++) {
				var status = json.statuses[i];
				lastStatusId = status.message_id;
				var li = document.createElement("LI");
				li.textContent = status.date + " - " + status.message;
				orderLogg.appendChild(li);
			}

			var clone = document.importNode(t, true);
			div.innerHTML="";
			div.appendChild(clone);

			var btnOrderDone = div.querySelector(".btnOrderDone");
			if(lastStatusId===2) {
				btnOrderDone.removeAttribute("disabled");
				btnOrderDone.dataset.id=orderId;
				btnOrderDone.addEventListener("click", setOrderAsDone, false);
			} else {
				btnOrderDone.disabled="disabled";
				btnOrderDone.removeEventListener("click", setOrderAsDone, false);
			}

			XioPop.center();
		});
	},




	setOrderAsDone = function(e) {
		var orderId = e.target.dataset.id;
		Ajax.getJSON("/admin/setOrderAsDone.php", {id:orderId}, function(json) {
			console.log("setOrderAsDone", json);
		});
	};






	return {
		init: init,
		loadAll: loadAll
	}


}());

