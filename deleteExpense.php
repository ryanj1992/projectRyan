<?php
include "dbconnect.php";

if(isset($_POST["expenseID"]))
{
    foreach($_POST["expenseID"] as $id)
    {
        $query = "DELETE FROM expenses WHERE expenseID = '".$id."'";
        mysqli_query($DBcon, $query);
    }
}
?>