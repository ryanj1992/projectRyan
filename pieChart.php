<?php
include "dbconnect.php";
?>

<?php

$id = $params['travel_id'];
mysqli_select_db($DBcon,"total_budget");

$sql="SELECT
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'transport') AS totalTransport,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'entertainment' AND travel_id = $id) AS totalEntertainment,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'phone' AND travel_id = $id) AS totalPhone,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'flights' AND travel_id = $id) AS totalFlights,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'accommodation' AND travel_id = $id) AS totalAccommodation,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'meals' AND travel_id = $id) AS totalMeals,
(SELECT SUM(amount) FROM expenses WHERE expenseType = 'miscellaneous' AND travel_id = $id) AS totalMiscellaneous
FROM expenses
GROUP BY totalTransport, totalEntertainment";
$result= $DBcon->query($sql);
$row =mysqli_fetch_assoc($result);


$data = array();
    $data[] = array("c" => array("0" => array("v" => "Entertainment", "f" => NULL), "1" => array("v" => (int)$row['totalEntertainment'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Transport", "f" => NULL), "1" => array("v" => (int)$row['totalTransport'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Phone", "f" => NULL), "1" => array("v" => (int)$row['totalPhone'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Flights", "f" => NULL), "1" => array("v" => (int)$row['totalFlights'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Accommodation", "f" => NULL), "1" => array("v" => (int)$row['totalAccommodation'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Meals", "f" => NULL), "1" => array("v" => (int)$row['totalMeals'], "f" => NULL)));
    $data[] = array("c" => array("0" => array("v" => "Miscellaneous", "f" => NULL), "1" => array("v" => (int)$row['totalMiscellaneous'], "f" => NULL)));


echo $format = '{
"cols":
[
{"id":"","label":"expenseType","pattern":"","type":"string"},
{"id":"","label":"entertainmentTotal","pattern":"","type":"number"}
],
"rows":'.json_encode($data).'}';
?>