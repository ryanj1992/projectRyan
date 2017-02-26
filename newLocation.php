<?php
session_start();
include_once 'dbconnect.php';
include 'menu.php';
?>
<head>
    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
</head>

<html>
<body>



<div class="col-md-4">
    <h2>Travel</h2>

    <div class="row">
        <form action="addTravel" method="post" name="addTravel" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="usr">Where are you going?</label>
                    <input type="text" class="form-control" name="travelLocation">

                    <label for="usr">Description:</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>

                    <label for="usr">Budget:</label>
                    <input type="text" class="form-control" name="budget">

                    <label for="usr">Currency:</label>
                    <select class="form-control" name="currency">
                        <option value="£">Pound (£)</option>
                        <option value="é">Euro (€)</option>
                        <option value="$">Dollar ($)</option>
                    </select>
                </div>

                <label for="usr">Start Date:</label>
                <div class="input-group input-append date" id="datePicker">
                    <input type="text" class="form-control" name="date"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
                <script>
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
                </script>
            </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-default">Add Travel Details</button>
    </div>
    </form>
</div>
</body>
</html>