
var angels, types;

var frmAngel = document.getElementById("frmAngel");
	frmAngel.addEventListener("submit", validateCheckout, false);


var btnChooseAngel = document.getElementById("btnChooseAngel");
	btnChooseAngel.addEventListener("click", showAngelList, false);


var randomMessage = document.getElementById("randomMessage");
var pAngel = document.getElementById("pAngel");
var h4Angel = document.getElementById("h4Angel");
var imgAngel = document.getElementById("imgAngel");
	imgAngel.addEventListener("click", showAngelList, false);
var angelSelect = document.getElementById("angelSelect");
var txtAngelId = document.getElementById("txtAngelId");
var txtTypeId = document.getElementById("txtTypeId");

var totalPrice = document.getElementById("totalPrice");
var totalText = document.getElementById("totalText");


var listAngels = document.getElementById("listAngels");
	listAngels.addEventListener("click", angelHandler, false);
var listTypes = document.getElementById("listTypes");
	listTypes.addEventListener("click", typeHandler, false);
var angelId, typeId;


loadAngels();
loadTypes();

var messages = [
	"Älskade syster!<br>Här kommer en vacker present till en vacker prinsessa. Varje gång du bär denna så är jag med dig vart du än är..<br><br>Älskar dig :)",
	"För att du är min ängel och prinsessa.<br>Jag älskar dig, nu och för alltid!<br><br>/din prins",
	"Hej Tilda!<br>Här får du en ängel som kan hänga på din mobiltelefon.<br>Kram från mormor",
	"Till min söta Mamma!<br>Kram från Johanna",
	"Tack för att du hjälper mig att ta mig igenom det svåra. Du är en ängel!",
	"Tänker på dig... Det finns inga ord...<br>Kram Veronica",
	"Beklagar din sorg, det finns inga ord som kan ge tröst! Skickar dig styrkekramar!<br>Varma hälsningar Kristina",
	"Bär med dig ängeln som en påminnelse om att det finns någon som tänker på dig. Och framför allt - glöm inte bort att stanna upp och andas!<br><br>Kram, Jenny",
	"En liten ängel till världens gulligaste lilla nyblivna mamma! Hälsa hela familjen, gratulera den nyblivna pappan och så hoppas jag att vi ses snart!<br>Stor kram från Marie (med hälsning från Rickard och de vilda gudbarnen också)",
	"Det finns Mirakel och för mig är det du.<br>Kram din man.",
	"God Jul, älskade mamma.",
	"Du är älskad och beundrad!<br>Grattis och God Jul",
	"Tack för att du finns i mitt liv.<br>Jag älskar dig så mycket!<br><br>//Din fästman",
	"Tack för att du alltid finns vid min sida och förgyller mitt liv. Det är du & jag i nöd och lust.<br><br>Med kärlek /din fru"
];
messages.sort(function() {return 0.5 - Math.random()})

/*
var tic=0;
var ticker = setInterval(function() {
	switch(tic) {	
		case 0:
		var mess = messages.shift();
		messages.push(mess);
		randomMessage.innerHTML = mess;
		randomMessage.style.opacity=1;
		break;
		
		case 8:
		randomMessage.style.opacity=0;
		break;
		
		case 9:
		tic = -1;
		break;	
	}
	
	tic++;
}, 1000);
*/





function loadTypes() {
	var xhr = new XMLHttpRequest();
	xhr.open("get", "load_types.php", true);
	xhr.onload = typesLoaded;
	xhr.send();	
}


function typesLoaded(e) {
	if(e.target.status===200) {
		types = JSON.parse(e.target.responseText);
		console.log("Types loaded", types);
		printTypes();
	}	
}

function printTypes() {
	listTypes.innerHTML = "";
	for(id in types) {
		var li = document.createElement("LI");
		var type = types[id];
		li.id = "type"+id;
		li.textContent = type.type + " (+" + type.price + " kr)";
		listTypes.appendChild(li);
	}
}




function loadAngels() {
	var xhr = new XMLHttpRequest();
	xhr.open("get", "load_angels.php", true);
	xhr.onload = angelsLoaded;
	xhr.send();	
}


function angelsLoaded(e) {
	if(e.target.status===200) {
		angels = JSON.parse(e.target.responseText);
		console.log("Angels loaded", angels);
		printAngels();
	}	
}

function printAngels() {
	listAngels.innerHTML = "";
	for(id in angels) {
		var li = document.createElement("LI");
		li.id = "angel"+id;
		var img = document.createElement("img");
		img.src="angels200/"+id+".jpg";
		var span = document.createElement("span");
		span.textContent = angels[id].name;
		
		li.appendChild(img);
		li.appendChild(span);
		listAngels.appendChild(li);
	}
}


function validateCheckout(e) {
	var abort = false;

	var errormessages = frmAngel.querySelectorAll("p.errormessage");
	for(var i=0; i<errormessages.length; i++) {
		errormessages[i].classList.add("hidden");
	};
	
		
	var inputs = frmAngel.querySelectorAll("input.required");
	var firstInvalid;
	for(var i=0; i<inputs.length; i++) {
		if(inputs[i].value.trim() === "") {
			if(!firstInvalid) {
				firstInvalid = inputs[i];
				abort = true;
			}
			inputs[i].classList.add("invalid");
			inputs[i].parentElement.querySelector(".errormessage").classList.remove("hidden");
			
		} else {
			inputs[i].classList.remove("invalid");
		}
	}

	if(abort) {
		e.preventDefault();
		firstInvalid.parentElement.scrollIntoView();
		firstInvalid.focus();
	}

}




function showAngelList(e) {
	angelSelect.style.display="block";
	frmAngel.style.display="none";
}




function angelHandler(e) {
	var target=e.target;
	
	while(target.nodeName!=="LI" && target!==listAngels) target=target.parentNode;
	
	var id = target.id.replace("angel","");
	if(angelId!==id) selectAngel(id);
	
	btnChooseAngel.textContent = "Ändra";
	
	console.log("Angel clicked", angels[id]);
}


function selectAngel(id) {
	var items = listAngels.querySelectorAll("li");
	for(var i=0; i<items.length; i++) {
		var item = items[i];
		if(item.id==="angel"+id) {
			item.classList.add("dimmed");
		} else {
			item.classList.remove("dimmed");
		}
	}
	var angel = angels[id];
	txtAngelId.value = id;
	txtAngelId.parentElement.querySelector(".errormessage").classList.add("hidden");
	pAngel.textContent = angel.bio;
	h4Angel.textContent = angel.name;
	imgAngel.src = "angels200/"+id+".jpg";
	
	angelSelect.style.display="none";
	frmAngel.style.display="block";
	updatePrice();
}

function typeHandler(e) {
	var target=e.target;
	console.log("type click", target, e);
	
	while(target.nodeName!=="LI" && target!==listTypes) target=target.parentNode;
	
	var id = target.id.replace("type","");
	if(typeId!==id) selectType(id);
}

function selectType(id) {
	var items = listTypes.querySelectorAll("li");
	txtTypeId.value = id;
	txtTypeId.parentElement.querySelector(".errormessage").classList.add("hidden");
	for(var i=0; i<items.length; i++) {
		var item = items[i];
		if(item.id==="type"+id) {
			item.classList.add("selected");
		} else {
			item.classList.remove("selected");
		}
	}
	updatePrice();
}

function updatePrice() {
	var angel = angels[txtAngelId.value];
	var type = types[txtTypeId.value];
	if(angel && type) {
		totalPrice.textContent = (parseInt(angel.price) + parseInt(type.price)) + " kr";
		totalText.textContent = "Total kostnad, inkl frakt:";
	}
}

function fill() {
	var angelIds = Object.keys(angels);
	txtAngelId.value = angelIds[Math.floor(Math.random() * angelIds.length)];
	
	var firstNames = ["Sven", "Pelle", "Råger", "Berit", "Betty", "Orvar", "Göran", "Lennart"];
	var lastNames = ["Svensson", "Gustafsson", "Al", "Bergström", "Frideborg", "Walter", "Karlsson", "Adolfsdotter"];
	var streets = ["Kungsgatan", "Hamngatan", "Drottninggatan", "Östra svängen", "Storgatan", "Myrslingan", "Herr Arnes väg"];
	var cities = ["Stockholm", "Göteborg", "Halmstad", "Lund", "Örebro", "Kalix", "Boden"];
	
	document.getElementById("txtSenderFirstName").value = firstNames[Math.floor(Math.random() * firstNames.length)];
	document.getElementById("txtSenderLastName").value = lastNames[Math.floor(Math.random() * lastNames.length)];
	document.getElementById("txtSenderEmail").value = (document.getElementById("txtSenderFirstName").value + "." + document.getElementById("txtSenderLastName").value + "@teodor.se").toLowerCase();
		
	document.getElementById("txtRecieverFirstName").value = firstNames[Math.floor(Math.random() * firstNames.length)];
	document.getElementById("txtRecieverLastName").value = lastNames[Math.floor(Math.random() * lastNames.length)];
	document.getElementById("txtRecieverStreet").value = streets[Math.floor(Math.random() * streets.length)] + " " + Math.floor(Math.random()*100);
	document.getElementById("txtRecieverZipCode").value =  Math.floor(Math.random()*90000)+10000;
	document.getElementById("txtRecieverCity").value = cities[Math.floor(Math.random() * cities.length)];
	
}

