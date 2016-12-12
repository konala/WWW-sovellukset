<?php
session_start();
//Establish database connection
try {
	$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

//Prepare statement and bind parameters
$stmt = $db->prepare("INSERT INTO `reservation`(`field_id`, `startTime`, `endTime`, `user_id`, `res_date`) VALUES (:f1, :f2, :f3, :f4, :f5)");
$stmt->bindParam(":f1", $_GET["field_id"]);
$stmt->bindParam(":f2", $_GET["startTime"]);
$stmt->bindParam(":f3", $_GET["endTime"]);
$stmt->bindParam(":f4", $_SESSION["user"]);
$stmt->bindParam(":f5", $_GET["res_date"]);
$stmt->execute();

//Another statement
$stmt2 = $db->prepare("SELECT id FROM `reservation` WHERE `field_id` = :f1 AND `startTime` = :f2 AND `endTime` = :f3 AND `user_id` = :f4 AND `res_date` = :f5");
$stmt2->bindParam(":f1", $_GET["field_id"]);
$stmt2->bindParam(":f2", $_GET["startTime"]);
$stmt2->bindParam(":f3", $_GET["endTime"]);
$stmt2->bindParam(":f4", $_SESSION["user"]);
$stmt2->bindParam(":f5", $_GET["res_date"]);
$stmt2->execute();
$rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$tmp = "Varaus tehty onnistuneesti " . $_GET["res_date"] . " klo " . $_GET["startTime"] . "-" . $_GET["endTime"];

//Create array of the data from above statements
if ($stmt && $stmt2) {
	$response = array(
			"reservation" => $tmp,
			"user_id" => $_SESSION["user"],
			"reservation_id" => $rows[0]["id"]
		);
	$response = json_encode($response);
	//Echo the array
	echo $response;
} else {
	$response = array(
			"reservation" => null,
			"user_id" => null,
			"reservation_id" => null
		);
	echo json_encode($response);
}
?>

