<?php
/* Universal function to make occasional database searches */

//DB connection
try {
	$db = new PDO('mysql:host=localhost;dbname=football;charset=utf8', 'www', 'www');
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}

//Types: 1: user, 2: field, 3: reservation
if ($_POST["type"] == 1) {
	$stmt = $db->prepare("SELECT * FROM `user` WHERE `id` = :f3");
	$stmt->bindParam(':f3', $_POST["user"]);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
?>