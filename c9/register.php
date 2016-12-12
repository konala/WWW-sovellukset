<?php
//Check that all needed fields are set and valid (e.g. passwords match, password is long enough)
if (isset($_POST["email"]) && isset($_POST["pw1"]) && isset($_POST["pw2"]) && isset($_POST["forename"]) && isset($_POST["lastname"]) &&isset($_POST["streetAddress"]) && isset($_POST["zip"]) && isset($_POST["organization"]) && isset($_POST["city"]) && ($_POST["pw1"] === $_POST["pw2"]) && !(empty($_POST["email"])) && !(empty($_POST["pw1"])) && strlen($_POST["pw1"]) > 8 && strlen($_POST["pw1"]) < 256) {
		//Establish database connection, prepare statement and bind parameters
		$servername = getenv('IP');
		$username = getenv('C9_USER');
		$password = "";
		$database = "c9";
		try {
		    $db = new PDO('mysql:host=' . $servername . ';dbname=' . $database . ';charset=utf8', $username, '');
		} catch (PDOException $e) {
		    echo "Connection failed: " . $e->getMessage();
		}
		$stmt = $db->prepare("INSERT INTO `user`(`fullname`, `address`, `pw_hash`, `email`, `organization`,`admin`) VALUES (:fullname, :address, :pw_hash, :email, :organization, 0)");
		$tmp = password_hash($_POST["pw1"], PASSWORD_DEFAULT); //Hash user's password for database
		$stmt->bindParam(':email', $_POST["email"]);
		$stmt->bindParam(':pw_hash', $tmp);
		$address = $_POST["streetAddress"] . " " . $_POST["zip"] . " " . $_POST["city"]; //Parse full address
		$stmt->bindParam(':address', $address);
		$fullname = $_POST["forename"] . " " . $_POST["lastname"]; //Parse full name
		$stmt->bindParam(':fullname', $fullname);
		$stmt->bindParam(':organization', $_POST["organization"]);
		$stmt->execute();
		//Registration succesful
		if ($stmt) {
			echo "<p id='login-register-succesful' style='max-width: 700px;margin: 0 auto;margin-top: 5px;background-color: rgba(92, 182, 57, 0.8);border-radius: 5px;padding: 5px 5px;font-variant: small-caps;font-size: 1.5em;text-align: center;'> Rekisteröinti onnistui!</p>";
		}
//If all the needed fields aren't set print out the form to user
} else {

?>

<div id="login-register-div">
	<div id="login-register-info">
		<p>Sähköpostiosoite</p>
		<p>Etunimi</p> 
		<p>Sukunimi</p> 
		<p>Joukkue/Organisaatio</p> 
		<p>Katuosoite</p> 
		<p>Postinumero</p> 
		<p>Kaupunki</p> 
		<p>Salasana</p> 
		<p>Salasana uudestaan</p>

	</div>
	<div id="login-register-form-div">
	<form id="login-register-form" method="post" action="index.php?p=register">
		<input type="email" name="email" placeholder="erkki.esimerkki@esimerkki.fi">
		<input type="text" name="forename" placeholder="Erkki">
		<input type="text" name="lastname" placeholder="Esimerkki">
		<input type="text" name="organization" placeholder="Erkki Esimerkin Palloseura">
		<input type="text" name="streetAddress" placeholder="Merkkikatu 2">
		<input type="number" name="zip" placeholder="12345">
		<input type="text" name="city" placeholder="Esimerkki">
		<input type="password" name="pw1" placeholder="Vähintään 9 merkkiä">
		<input type="password" name="pw2" placeholder="Vähintään 9 merkkiä">
		<input type="submit" name="submitRegister" value="Rekisteröidy!">

	</form>
	</div>
</div>

<?php
//Print error dialog
if (isset($_POST["email"])) {
		?> <p id="login-register-error" title="Virhe!"></p>
		<script>
				$("#login-register-error").html("Täytit tietosi väärin!");
				$("#login-register-error").dialog({
				buttons: {
					Sulje: function() {$(this).dialog("close");}
				},
				height: 270,
				width: 300,
				show: {
					effect: "blind",
					duration: 300
				},
				hide: {
					effect: "blind",
					duration: 300
				},
				resizable: false,
				modal: true

			});
			</script><?php
	}
}
?>
<!-- Dialog styling -->
<style type="text/css">
	.ui-widget-header, .ui-state-default, ui-button {
			background: #b9cd6d;
            border: 1px solid #b9cd6d;
            color: #FFFFFF;
           
	}
</style>