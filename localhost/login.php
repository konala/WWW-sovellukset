<?php
//Check whether user is logged in or not
if (isset($_SESSION["user"])) {
	logged();
} else {
	notLogged();
}
function notLogged() {
	//Check if email and password are set
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www'); //Database connection
		$stmt = $db->prepare("SELECT * FROM user WHERE email = :email"); //Statement
		$stmt->bindParam(':email', $_POST['email']);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$match = false;
		//Verify password hash
		if (!empty($rows)) {
			$match = password_verify($_POST['password'], $rows[0]['pw_hash']);
		}
		//If passwords match
		if ($match) {
			$_SESSION["user"] = $rows[0]["id"]; //Set currently logged in user
			$_SESSION["email"] = $rows[0]["email"];
			header("Location: index.php?p="); //After succesful log in go back to front page
			die();
			
		//If passwords didn't match	
		} else {
			?>
			<!-- Let user know that something went wrong -->
			<div id='login-incorrect' title='Huomio!'>
			<script>
				$("#login-incorrect").html("Väärä sähköposti tai salasana!");
				$("#login-incorrect").dialog({
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
			</script>
			</div>
			<div id="login-login">
				<p id="login-title"> Kirjaudu sisään </p>
				<form method="post" action="index.php?p=login">
					<input id="login-form-email" class="login-form" type="email" name="email" placeholder="Käyttäjätunnus (email)">
					<input id="login-form-password" class="login-form" type="password" name="password" placeholder="Salasana">
					<input id="login-form-submit" class="login-form" type="submit" name="submitLogin" value="Kirjaudu">
				</form>
				<p id="login-register-title"> Eikö sinulla ole vielä tunnuksia?</p>
				<a id="login-register" href="index.php?p=register">Rekisteröidy!</a>
			</div>
			<?php
		}
	//If password and email aren't set show login screen
	} else {

?>
<!-- Login screen -->
<div id="login-login">
	<p id="login-title"> Kirjaudu sisään </p>
	<form method="post" action="index.php?p=login">
		<input id="login-form-email" class="login-form" type="email" name="email" placeholder="Käyttäjätunnus (email)">
		<input id="login-form-password" class="login-form" type="password" name="password" placeholder="Salasana">
		<input id="login-form-submit" class="login-form" type="submit" name="submitLogin" value="Kirjaudu">
	</form>
	<p id="login-register-title"> Eikö sinulla ole vielä tunnuksia?</p>
	<a id="login-register" href="index.php?p=register">Rekisteröidy!</a>
</div>
<?php 
	}
}

function logged() {
?>
<!-- User is logged in, show just a log out button -->
<div id="login-logout">
	<a href="index.php?p=logout">Kirjaudu ulos</a>
</div>

<?php
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