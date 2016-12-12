<!-- Basic structure of the web page-->

<?php
//PHP-functions for the front controller
function siteHeader() {
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">

	<title>Varausjärjestelmä 0.1</title>
</head>
<header>
	<div id="page-title-div">
		<p id="pageTitle">Fudiskenttien varausjärjestelmä</p><div id="football"></div>
	</div>
</header>
<div id="navDiv">
<nav>
<?php 
	//Show currently logged in user
	if (isset($_SESSION["user"])) {
		?> <div id="loginWrapper"><div id="loginTicker"><div id="loginInfo">Kirjautuneena <span><?php echo $_SESSION["email"]?></span>!</div></div></div><?php
	}
	?>
<!-- Navigation structure -->
<script type="text/javascript" src="layout.js"></script>
	<ul id="mainNav">
		<li id="menuButton" style="display: none;">☰</li>
	</ul>
	<ul id="navigation">
		<li><a class="navigation" href="index.php?p=">Etusivu</a></li>
		<?php
		//Show these only if user is logged in
		if (isset($_SESSION["user"])) {
		?>
		<li><a class="navigation" href="index.php?p=reservation">Kentän Varaaminen</a></li>
		<li><a class="navigation" href="index.php?p=myReservations">Omat Varaukset</a></li>
		<?php
		}
		?>
		<li><a class="navigation" href="index.php?p=fields">Kentät</a></li>
		<li><a class="navigation" href="index.php?p=login">Kirjautuminen</a></li>
		<!-- <li><a class="navigation" href="index.php?p=testi">Testi</a></li> -->
	</ul>
	

</nav>
</div>
<?php
}







function siteBody() {
?>
<body>
	<div id="mainDiv">
		<p>Tämä on varausjärjestelmän etusivu.</p>
		
	</div>
</body>
<?php
}

?>
</html>