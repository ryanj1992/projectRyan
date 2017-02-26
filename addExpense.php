<?php
session_start();
include_once 'dbconnect.php';

$stmt = $DBcon->prepare("INSERT INTO expenses (paymentType, amount, paymentDate, comment, expenseType, user_id, travel_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssii", $paymentType, $amount, $date, $comment, $expenseType, $id, $travelId);

$id = $_SESSION['userSession'];
$paymentType =strip_tags($_POST['paymentType']);
$comment = strip_tags($_POST['comment']);
$amount = strip_tags($_POST['amount']);
$expenseType = strip_tags($_POST['expenseType']);
$date = strip_tags($_POST['date']);
$travelId = strip_tags($_POST['travelId']);
$stmt->execute();

header('Location: ' . $_SERVER["HTTP_REFERER"] );

$stmt->close();
$DBcon->close();