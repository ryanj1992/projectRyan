<?php
session_start();
include_once 'dbconnect.php';
include 'menu.php';
$id = $params['travel_id'];
?>
<html>
<head>
    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<div class="container">
    <div class="col-md-12">

        <?php

        mysqli_select_db($DBcon, "total_spent");
        $sql = "SELECT travelLocation, totalBudget FROM travel WHERE travel_id = $id";
        $result = $DBcon->query($sql);
        $row = mysqli_fetch_array($result);
        $sql2="SELECT SUM(amount) AS grandTotal FROM expenses WHERE travel_id = $id";
        $result2 = $DBcon->query($sql2);
        $row2 = mysqli_fetch_array($result2);

        $sql3="SELECT SUM(amount) AS entertainmentTotal FROM expenses WHERE travel_id = $id AND expenseType = 'entertainment'";
        $result3 = $DBcon->query($sql3);
        $row3 = mysqli_fetch_array($result3);
        echo "<h2>" . $row['travelLocation'] . "</h2>";

        ?>

    </div>
    <div class="row" id="totalSpent">
        <script type="text/javascript">
            $( document ).ajaxComplete(function() {
                $( "#progressbar" ).progressbar({
                    value:<?php echo $row2['grandTotal'] ?>,
                    max:<?php echo $row['totalBudget'] ?>
                });
            } );
        </script>
    </div>
    <div class="addMore">
        <button class="alert alert-info"><?php echo "Add Expenses" ?><i class="fa fa-arrow-down fa-lg text-danger my-icon" aria-hidden="true"></i></button>
    </div>
    <div class ="slideToggle">
            <form id="addExpense" enctype="multipart/form-data">
                <div class ="addToExpenses">
                    <label for="usr">Type of Expense:</label>
                    <select class="form-control" name="expenseType">
                        <option value="Entertainment">Entertainment</option>
                        <option value="Flights">Flights</option>
                        <option value="Transport">Transport</option>
                        <option value="Accommodation">Accommodation</option>
                        <option value="Meals">Meals</option>
                        <option value="Phone">Phone</option>
                        <option value="Miscellaneous">Miscellaneous</option>
                    </select>

                    <label for="usr">Amount:</label>
                    <input type="number" class="form-control" name="amount">

                    <label for="usr">Type of Payment:</label>
                    <select class="form-control" name="paymentType">
                        <option value="Cash">Cash</option>
                        <option value="CreditCard">Credit Card</option>
                    </select>

                    <label for="usr">Comment:</label>
                    <input type="text" class="form-control" name="comment">

                    <label for="usr">Date Purchased:</label>
                    <input type="text" class="form-control" name="date" id ="datePicker">
                    <?php echo "<input type='hidden' id='travel_id' name = 'travelId' value = " . $id . ">"; ?>

                </div>
                <div class="modal-footer">
                    <div id="result">
                        Expense Added!
                    </div>
                    <input type="submit" class="btn btn-info btn-md"
                           value="Add Expense">
                </div>
            </form>
    </div>
    <div id="toggle">
        <button class="alert alert-success btn-sm">Show Table</button>
        <button class="alert alert-success btn-sm">Show Pie Chart</button>
    </div>

        <div id="responsecontainer">
            <script>
                function deleteExpense() {

                    if (confirm("Are you sure you want to delete this?")) {
                        var id = [];

                        $(':checkbox:checked').each(function (i) {
                            id[i] = $(this).val();
                        });

                        if (id.length === 0) //tell you if the array is empty
                        {
                            alert("Please Select atleast one checkbox");
                        }
                        else {
                            $.ajax({
                                url: '/deleteExpense',
                                method: 'POST',
                                data: {expenseID: id},
                                success: function () {
                                    for (var i = 0; i < id.length; i++) {
                                        $('tr#' + id[i] + '').css('background-color', '#ccc');
                                        $('tr#' + id[i] + '').fadeOut('slow');
                                    }
                                    drawChart();
                                    ajaxCall();
                                }

                            });
                        }

                    }
                    else {
                        return false;
                    }
                }
            </script>
    </div>

    <div id="deleteButton" align="center">
        <button type="button" onclick="deleteExpense()" name="delete" id="delete" class="btn btn-danger">Delete</button>
    </div>

    <div id="chart_div"></div>

    <script>

        $(document).ready(function(){
        $("#result").hide();
        $('.addMore').click(function(){
            $child=$(this).children('i');
            $child.toggleClass("fa-arrow-down").toggleClass("fa-arrow-up");
        });
    });

        $('#toggle > button').click(function() {
            var ix = $(this).index();

            $('#responsecontainer').toggle( ix === 0 );
            $('#deleteButton').toggle( ix === 0 );
            $('#chart_div').toggle( ix === 1 );
        });

        // Load the Visualization API and the piechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var jsonData = $.ajax({
                url: "/pieChart/<?php echo $id ?>",
                dataType: "json",
                async: false
            }).responseText;

            var options = {
                title: 'My Expenses'
            };

            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, {width: 750, height: 500});
        }



        $(document).ready(function () {
        $('#datePicker')
            .datepicker({
                format: 'dd/mm/yyyy'
            })
            .on('changeDate', function (e) {
                // Revalidate the date field
                $('#eventForm').formValidation('revalidateField', 'date');
            });

        $('#eventForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
        });
    });

    $(".addMore").click(function () {

        $header = $(this);
        //getting the next element
        $content = $header.next();
        //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
        $content.slideToggle(500, function () {
            //execute this after slideToggle is done
            //change text of header based on visibility of content div
        });

    });

    // Variable to hold request
    var request;

    // Bind to the submit event of our form
    $("#addExpense").submit(function (event) {

        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();

        // Abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);

        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");

        // Serialize the data in the form
        var serializedData = $form.serialize();

        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "/addExpense",
            type: "post",
            data: serializedData
        });

        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR) {
            // show successfully for submit message
            $("#result").show().fadeOut(3000);
            var form = document.getElementById("addExpense");
            form.reset();
            ajaxCall(); // To output when the page loads
            drawChart();
            // setInterval(ajaxCall, (2 * 1000)); // x * 1000 to get it in seconds
        });

        /* On failure of request this function will be called  */
        request.fail(function () {

            // show error
            $("#result").html('There is error while submit');
        });

        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            $inputs.prop("disabled", false);
        });

    });

    function ajaxCall() {
        $.ajax({
            url: "/getExpenses/<?php echo $id ?>",
            success: (function (result) {
                $("#responsecontainer").html(result);
                totalSpent();
            })
        })
    }
    ajaxCall();

    function totalSpent() {
        $.ajax({
            url: "/totalSpent/<?php echo $id ?>",
            success: (function (result) {
                $("#totalSpent").html(result);
            })
        })
    }

</script>
</body>
</html>
