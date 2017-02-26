<?php
include "dbconnect.php";

$travelID = $params['travel_id'];

mysqli_select_db($DBcon,"total_budget");
$sql2="SELECT totalBudget FROM travel WHERE travel_id = $travelID";
$result2 = $DBcon->query($sql2);
$row2 = mysqli_fetch_array($result2);

mysqli_select_db($DBcon,"total_spent");
$sql="SELECT SUM(amount) AS totalSpent FROM expenses WHERE travel_id = $travelID";
$result = $DBcon->query($sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        if ($row['totalSpent'] > 0) {
            echo "Total spent on this holiday £" . $row['totalSpent'] . "/£" . $row2['totalBudget'];
            echo "<div class='row' id='progressbar'></div>";
        }
        else{
            echo "Your budget for this holiday is ". "£". $row2['totalBudget'];
        }
    }
}
mysqli_close($DBcon);
?>
