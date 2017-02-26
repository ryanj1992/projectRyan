<?php
include "dbconnect.php";
?>

<?php
$id = $params['travel_id'];
mysqli_select_db($DBcon,"total_budget");
$sql2="SELECT SUM(amount) AS grandTotal FROM expenses WHERE travel_id = $id";
$result2 = $DBcon->query($sql2);
$row2 = mysqli_fetch_array($result2);

mysqli_select_db($DBcon,"ajax_demo");
$sql="SELECT expenseType, comment, paymentType, paymentDate, amount, expenseID FROM expenses WHERE travel_id = $id";
$result = $DBcon->query($sql);

echo "<table id ='myTable'>
<tr>
<th>Expense Type</th>
<th>Comment</th>
<th>Payment Type</th>
<th>Payment Date</th>
<th>Amount</th>
<th>Delete</th>
</tr>";

if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_array($result)) {
    echo "<tbody>";?>
    <tr id="<?php echo $row["expenseID"]; ?>" > <?php
    echo "<td>" . $row['expenseType'] . "</td>";
    echo "<td>" . $row['comment'] . "</td>";
    echo "<td>" . $row['paymentType'] . "</td>";
    echo "<td>" . $row['paymentDate'] . "</td>";
    echo "<td>"."£" . $row['amount'] . "</td>";
    echo "<td><input type='checkbox' name='id' class='delete_expense' value=".$row['expenseID']."></td>";
    echo "</tr>";
    echo "</tbody>";
}
   echo "<tfoot>";
   echo "<tr>";
   echo "<th id='total' colspan='4'>Total Spent:</th>";
   echo "<td style='font-weight: 700'>"."£".$row2['grandTotal']."</td>";
   echo "</tr>";
   echo "</tfoot>";
   echo "</table>"; ?>
        
        <?php
}
        else{

        }
mysqli_close($DBcon);
?>


