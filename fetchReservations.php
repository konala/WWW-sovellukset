<?php
//Establish database connection
try {
	$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
//Prepare statement and bind parameters
$stmt = $db->prepare("SELECT * FROM reservation WHERE field_id = :f1 AND `res_date` >= :f2;");
$stmt->bindParam(":f1", $_GET["field_id"]);
$today = date("Y-m-d");
$stmt->bindParam(":f2", $today);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Return JSON encoded data
echo json_encode($rows);

?>