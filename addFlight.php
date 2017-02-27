<?php
session_start();
include_once 'dbconnect.php';

$flight = unserialize($_POST['addFlight']);

extract($flight);

$stmt = $DBcon->prepare("INSERT INTO flights (saleTotal, user_id, departureTime, arrivalTime, origin, destination) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $saleTotal2, $id, $departureTime, $arrivalTime, $origin, $destination);

$id = $_SESSION['userSession'];
$saleTotal2 = substr($saleTotal, 3);
$stmt->execute();

if($stmt) {
    echo "New records created successfully";
}
else{
    echo "Error";
}

$stmt->close();
$DBcon->close();