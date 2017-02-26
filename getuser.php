<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, td, th {
            border: 1px solid black;
            padding: 5px;
        }

        th {text-align: left;}
    </style>
</head>
<body>

<?php
$q = intval($_GET['q']);
include "dbconnect.php";

mysqli_select_db($DBcon,"ajax_demo");
$sql="SELECT * FROM travel WHERE travel_id = '".$q."'";
$result = mysqli_query($DBcon,$sql);

echo "<table>
<tr>
<th>user_id</th>
<th>Username</th>
<th>Email</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['travel_id'] . "</td>";
    echo "<td>" . $row['travelLocation'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "</tr>";
}
echo "</table>";
mysqli_close($DBcon);
?>
</body>
</html>