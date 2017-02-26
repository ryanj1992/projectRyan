<?php
session_start();
include_once 'dbconnect.php';

$stmt = $DBcon->prepare("INSERT INTO travel (travelLocation, user_id, description, totalBudget, currency, startDate) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisiss", $travelLocation, $id, $description, $budget, $currency, $date);

$id = $_SESSION['userSession'];
$travelLocation =strip_tags($_POST['travelLocation']);
$description = strip_tags($_POST['description']);
$budget = strip_tags($_POST['budget']);
$currency = strip_tags($_POST['currency']);
$date = strip_tags($_POST['date']);
$stmt->execute();

if($stmt) {
    echo "New records created successfully";
}
else{
    echo "Error";
}

$stmt->close();
$DBcon->close();