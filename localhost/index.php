<?php
/* Front controller */

include "utils.php";
include "fields.php";
session_start();
require_once("utils.php");
// Always redirect user to index.php?p=
if (!(isset($_GET["p"]))) {
	header("Location: index.php?p=");
	die();
//Else redirect based on parameter p
} else {
	siteHeader();
	if ($_GET["p"] == "fields") {
		if (file_exists("fields.php")) {
			$tmp = new Field();
			$tmp->createFieldTable();
		} else {
			print("404");
		}
	} else if ($_GET["p"] == "reservation" && isset($_SESSION["user"])) {
		if (file_exists("reservation.php")) {
			require("reservation.php");
		} else {
			print("404");
		}
	} else if ($_GET["p"] == "register") {
		if (file_exists("register.php")) {
			require("register.php");
		} else {
			print("404");
		}
	} else if ($_GET["p"] == "login") {
		if (file_exists("login.php")) {
			require("login.php");
		} else {
			print("404");
		}
	} else if ($_GET["p"] == "myReservations" && isset($_SESSION["user"])) {
		if (file_exists("myReservations.php")) {
			require("myReservations.php");
		} else {
			print("404");
		}
	} else if ($_GET["p"] == "logout") {
		session_unset();
		header("Location: index.php?p=");
		die();
	
	} else if ($_GET["p"] == "testi") {
		require("testi.php");
    } else {
		siteBody();
	}

}
	

?>