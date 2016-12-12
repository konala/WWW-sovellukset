<?php
/* Universal function to make occasional database searches */

//DB connection
$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "c9";
try {
    $db = new PDO('mysql:host=' . $servername . ';dbname=' . $database . ';charset=utf8', $username, '');
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