<?php

//Establish database connection
try {
	$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
//Prepare statement and bind parameters
$stmt = $db->prepare("DELETE FROM `reservation` WHERE `id` = :f1");
$stmt->bindParam(":f1", $_GET["res_id"]);
$stmt->execute();
?>