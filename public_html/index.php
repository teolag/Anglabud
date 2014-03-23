<?php require "header.php"; ?>
		
<!--
<ul>
	<li>Skicka flera änglar</li>
	<li>karta vart änglarna ska</li>
	<li>historik sida för kunden</li>
	<li>maila om ingen betalning</li>
	<li>ingen statusupprepning vid reciept och ipn</li>
	<li>testa invoice</li>			
</ul>
-->

<form id="frmAngel" action="checkout.php" method="post">

	<fieldset>
		<h3>1. Välj en ängel</h3>
		<p class="errormessage hidden">För att veta vilken ängel som ska skickas måste du välja angel och typ av smycke</p>
		<img src="" id="imgAngel" />
		<h4 id="h4Angel"></h4>
		<p id="pAngel"></p>
		<input type="hidden" class="required" name="angel[id]" id="txtAngelId" />
		<input type="hidden" class="required" name="angel[type_id]" value="1" id="txtAngelType" />
		<button type="button" id="btnChooseAngel">Välj</button>
	</fieldset>
	
	<q id="randomMessage"></q>

	<fieldset>
		<h3>2. Meddelande &amp; leverans</h3>
		<p class="description">Om du vill att ditt bud ska skickas till mottagaren vid ett visst datum kan du fylla i det här under. Lämnar du fältet tomt skickas ängeln så snart den är färdig.</p>
		<p class="errormessage hidden"></p>
		<label for="txtMessage" class="full">Meddelande till mottagaren</label>
		<textarea name="delivery[message]" id="txtMessage" class="full"></textarea>
		<br>
		<label for="txtSendDate" class="full">När ska ängeln skickas</label>
		<input type="text" id="txtSendDate" name="delivery[date]" class="full" />
		<br>
		<label for="txtOther" class="full">Övriga kommentarer och önskemål</label>
		<textarea name="delivery[other]" id="txtOther" class="full"></textarea>
			
	</fieldset>

	<fieldset>
		<h3>3. Mottagarens uppgifter</h3>
		<p class="errormessage hidden">För att änglabudet ska hitta fram till rätt mottagare krävs att du fyllt i de rödmarkerade fälten nedan</p>
		<label for="txtRecieverFirstName">Namn*:</label>
		<input type="text" name="reciever[first_name]" id="txtRecieverFirstName" class="half required" placeholder="förnamn" />
		<input type="text" name="reciever[last_name]" id="txtRecieverLastName" class="half required" placeholder="efternamn" />
		<br>
		<label for="txtRecieverStreet">Gatuadress*:</label>
		<input type="text" name="reciever[street]" id="txtRecieverStreet"  placeholder="gata" class="required" />
		<br>
		<label for="txtRecieverZipCode">Postadress*:</label>
		<input type="number" name="reciever[zip_code]" id="txtRecieverZipCode" class="half required" placeholder="postnummer" />
		<input type="text" name="reciever[city]" id="txtRecieverCity" class="half required" placeholder="ort" />
		<br>
		<label for="txtRecieverCountry">Land*:</label>
		<input type="text" name="reciever[country]" id="txtRecieverCountry" value="Sverige" class="required" />
	</fieldset>

	<fieldset>
		<h3>4. Dina uppgifter</h3>
		<p class="description">Dina uppgifter kommer inte visas för mottagaren. Vill du säga vem ängeln kommer från får du skriva det i meddelandet.</p>
		<p class="errormessage hidden">För att betalningen ska bli rätt och beställningen ska gå igenom krävs att du fyller i de rödmarkerade fälten nedan</p>
		<label for="txtSenderFirstName">Namn*:</label>
		<input type="text" name="sender[first_name]" id="txtSenderFirstName" class="half required" placeholder="förnamn" />
		<input type="text" name="sender[last_name]" id="txtSenderLastName" class="half required" placeholder="efternamn" />
		<br>
		<label for="txtSenderEmail">E-post*:</label>
		<input type="text" name="sender[email]" id="txtSenderEmail" placeholder="epost" class="required" />
		<br>
		<label for="txtSenderPhone">Telefon:</label>
		<input type="text" name="sender[phone]" id="txtSenderPhone" placeholder="telefonnummer" />
		<br><br>
		<label for="txtSenderFound" class="full">Hur hittade du hit:</label>
		<input type="text" name="sender[found]" id="txtSenderFound" placeholder="" class="full" />
	</fieldset>

	<img src='https://www.payson.se/sites/all/files/images/external/payson150x55.png' />
	<button type="submit" id="btnSubmitOrder">Till kassan</button>
</form>

<div id="angelSelect">
	<h3>Välj ängel</h3>
	<ul id="listAngels"></ul>
</div>

<script src="js/main.js"></script>

<?php require "footer.php"; ?>