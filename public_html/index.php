<?php require "header.php"; ?>

<form id="frmAngel" action="checkout.php" method="post">

	<fieldset>
		<h3>1. Välj en ängel</h3>
		<p class="errormessage hidden">Vänligen välj någon av änglarna i sortimentet genom att klicka på frågetecknet eller på "Välj" knappen</p>
		<img src="/img/no-angel.png" id="imgAngel" />
		<h4 id="h4Angel"></h4>
		<p id="pAngel"></p>
		<input type="hidden" class="required" name="angel[id]" id="txtAngelId" />
		<button type="button" id="btnChooseAngel">Välj</button>
	</fieldset>
	
	<fieldset>
		<h3>2. Välj smyckestyp</h3>
		<p class="errormessage hidden">Välj vilken typ av smycke du vill skicka din ängel som</p>
		<input type="hidden" class="required" name="angel[type_id]" id="txtTypeId" />
		
		<ul id="listTypes"></ul>
	</fieldset>
	
	<!--<q id="randomMessage"></q>-->

	<fieldset>
		<h3>3. Meddelande &amp; leverans</h3>
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
		<h3>4. Mottagarens uppgifter</h3>
		<p class="errormessage hidden">För att änglabudet ska hitta fram till rätt mottagare krävs att du fyllt i de rödmarkerade fälten nedan</p>
		<label class="before" for="txtRecieverFirstName">Namn*:</label>
		<input type="text" name="reciever[first_name]" id="txtRecieverFirstName" class="half required" placeholder="förnamn" />
		<input type="text" name="reciever[last_name]" id="txtRecieverLastName" class="half required" placeholder="efternamn" />
		<br>
		<label class="before" for="txtRecieverStreet">Gatuadress*:</label>
		<input type="text" name="reciever[street]" id="txtRecieverStreet"  placeholder="gata" class="required" />
		<br>
		<label class="before" for="txtRecieverZipCode">Postadress*:</label>
		<input type="number" name="reciever[zip_code]" id="txtRecieverZipCode" class="half required" placeholder="postnummer" />
		<input type="text" name="reciever[city]" id="txtRecieverCity" class="half required" placeholder="ort" />
		<br>
		<label class="before" for="txtRecieverCountry">Land*:</label>
		<input type="text" name="reciever[country]" id="txtRecieverCountry" value="Sverige" class="required" />
	</fieldset>

	<fieldset>
		<h3>5. Dina uppgifter</h3>
		<p class="description">Dina uppgifter kommer inte visas för mottagaren. Vill du säga vem ängeln kommer från får du skriva det i meddelandet.</p>
		<p class="errormessage hidden">För att betalningen ska bli rätt och beställningen ska gå igenom krävs att du fyller i de rödmarkerade fälten nedan</p>
		<label class="before" for="txtSenderFirstName">Namn*:</label>
		<input type="text" name="sender[first_name]" id="txtSenderFirstName" class="half required" placeholder="förnamn" />
		<input type="text" name="sender[last_name]" id="txtSenderLastName" class="half required" placeholder="efternamn" />
		<br>
		<label class="before" for="txtSenderEmail">E-post*:</label>
		<input type="text" name="sender[email]" id="txtSenderEmail" placeholder="epost" class="required" />
		<br>
		<label class="before" for="txtSenderPhone">Telefon:</label>
		<input type="text" name="sender[phone]" id="txtSenderPhone" placeholder="telefonnummer" />
		<br><br>
		<label class="before" for="txtSenderFound" class="full">Hur hittade du hit:</label>
		<input type="text" name="sender[found]" id="txtSenderFound" placeholder="" class="full" />
	</fieldset>

	<div id="checkout">
		<span id="totalText"></span>
		<span id="totalPrice"></span>
		<button type="submit" id="btnSubmitOrder"></button>
	</div>
</form>

<div id="angelSelect">
	<h3>Välj ängel</h3>
	<ul id="listAngels"></ul>
</div>

<script src="js/main.js"></script>

<?php require "footer.php"; ?>