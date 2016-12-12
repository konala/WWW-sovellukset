<?php
//Establish database connection
try {
	$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
//Prepare statement and bind parameters
$stmt = $db->prepare("SELECT * FROM `reservation` WHERE `user_id` = :f1 AND `res_date` >= :f2");
$stmt->bindParam(":f1", $_GET["user_id"]);
$today = date("Y-m-d");
$stmt->bindParam(":f2", $today);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Another statement
$stmt2 = $db->prepare("SELECT * FROM `field` WHERE 1=1;");
$stmt2->execute();
$fields = $stmt2->fetchAll(PDO::FETCH_ASSOC);
//Create array of rows returned by the two above statements
$arr = array(
		"reservations" => $rows,
		"fields" => $fields
	);
//Return JSON encoded array
if ($stmt && $stmt2) {
	echo json_encode($arr);
}


?>