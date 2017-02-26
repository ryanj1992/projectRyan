<?php
session_start();
include_once 'dbconnect.php';
include 'menu.php';
?>
<html>
<head>
    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
</head>
<body>
<div class="col-md-12">
    <h2>Your Holidays</h2>
    <div class="row">
        <?php
        $id = $_SESSION['userSession'];
        $sql = "SELECT t.travelLocation, t.startDate, t.totalBudget, t.currency, t.travel_id FROM travel t WHERE user_id = $id";
        $result = $DBcon->query($sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="container">
                            <div class="header alert alert-info"><span><?php echo $row['travelLocation'] ?></span>
                                <i class="fa fa-arrow-down fa-lg text-danger my-icon" aria-hidden="true"></i>
                            </div>

                     <?php  $sql2 = "SELECT SUM(amount) AS totalSpent FROM expenses WHERE travel_id = " . $row['travel_id'] ." GROUP BY travel_id";
                            $result2 = $DBcon->query($sql2);
                            if (mysqli_num_rows($result2) > 0) {
                     // output data of each row
                     while ($row2 = mysqli_fetch_assoc($result2)) {
                     ?>
                            <div class="content">
                                <h1>You have
                                    spent<?php echo " " . $row['currency'] . $row2['totalSpent'] . "/" . $row['currency'] . $row['totalBudget'] ?>
                                    of your budget</h1>
                                <?php
                                }
                                }
                                ?>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
                                        40% Spent
                                    </div>
                                </div>
                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-info btn-md" data-toggle="modal"
                                        data-target="#myModal">Add Expense <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                                <div id="result"></div>

                                <!-- Modal -->
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Add Expense</h4>
                                            </div>
                                            <form id="addExpense" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <label for="usr">Type of Expense:</label>
                                                    <select class="form-control" name="expenseType">
                                                        <option value="entertainment">Entertainment</option>
                                                        <option value="flights">Flights</option>
                                                        <option value="transport">Transport</option>
                                                        <option value="accommodation">Accommodation</option>
                                                        <option value="meals">Meals</option>
                                                        <option value="phone">Phone</option>
                                                        <option value="miscellaneous">Miscellaneous</option>
                                                    </select>

                                                    <label for="usr">Amount:</label>
                                                    <input type="number" class="form-control" name="amount">

                                                    <label for="usr">Type of Payment:</label>
                                                    <select class="form-control" name="paymentType">
                                                        <option value="cash">Cash</option>
                                                        <option value="creditCard">Credit Card</option>
                                                    </select>

                                                    <label for="usr">Comment:</label>
                                                    <input type="text" class="form-control" name="comment">

                                                    <label for="usr">Date Purchased:</label>
                                                    <input type="text" class="form-control" name="date">
                                                    <?php echo "<input type='hidden' name = 'travelId' value = " . $row['travel_id'] . ">" ?>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info btn-md"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    <input type="submit" class="btn btn-info btn-md"
                                                           value="Add Expense">
                                                </div>

                                                <script>
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
                                                            url: "/addExpense.php",
                                                            type: "post",
                                                            data: serializedData
                                                        });

                                                        // Callback handler that will be called on success
                                                        request.done(function (response, textStatus, jqXHR) {
                                                            // show successfully for submit message
                                                            $("#result").html('Expense Added!');
                                                            $('#myModal').modal('hide');
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
                                                </script>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <h2>Your Expenses</h2>
                            </div>
                        </div>
                        <?php
            }
        } ?>

        <script>
            function showUser(str) {
                if (str == "") {
                    document.getElementById("txtHint").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("txtHint").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","getuser.php?q="+str,true);
                    xmlhttp.send();
                }
            }
        </script>

        <form>
            <select name="users" onchange="showUser(this.value)">
                <option value="">Select a person:</option>
                <option value="13">Ryan</option>
                <option value="14">Lois Griffin</option>
                <option value="16">Joseph Swanson</option>
                <option value="4">Glenn Quagmire</option>
            </select>
        </form>
        <br>
        <div id="txtHint"><b>Person info will be listed here...</b></div>

        <script>

            $(document).ready(function(){
                $('.header').click(function(){
                    $child=$(this).children('i');
                    $child.toggleClass("fa-arrow-down").toggleClass("fa-arrow-up");
                });
            });

            $(".header").click(function () {

                $header = $(this);
                //getting the next element
                $content = $header.next();
                //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
                $content.slideToggle(500, function () {
                    //execute this after slideToggle is done
                    //change text of header based on visibility of content div
                });

            });
        </script>

    </div>
</div>
</body>
</html>
