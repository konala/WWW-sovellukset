<?php
//Establish database connection
$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "c9";
try {
    $db = new PDO('mysql:host=' . $servername . ';dbname=' . $database . ';charset=utf8', $username, '');
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
//Prepare statement and bind parameters
$stmt = $db->prepare("DELETE FROM `reservation` WHERE `id` = :f1");
$stmt->bindParam(":f1", $_GET["res_id"]);
$stmt->execute();
?>