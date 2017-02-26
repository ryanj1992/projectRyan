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

        <?php
        $id = $_SESSION['userSession'];
        $sql = "SELECT travelLocation, startDate, totalBudget, currency, travel_id FROM travel WHERE user_id = $id";
        $result = $DBcon->query($sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-12">
                    <div class="container">
                        <a href="holiday/<?php echo $row['travel_id'] ?>" class="btn btn-info" role="button"><?php echo $row['travelLocation'] ?></a></button>
                    </div>
                </div>
                <?php
            }
        } ?>
    </div>

    <script>

        $(document).ready(function () {
            $('.header').click(function () {
                $child = $(this).children('i');
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
