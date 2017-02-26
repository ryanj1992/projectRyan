<?php
session_start();
include_once 'dbconnect.php';
include 'menu.php';
?>
<head>
    <!-- Include Bootstrap Datepicker -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.16.1/lodash.min.js"></script>
    <script src="https://unpkg.com/fuse.js@2.5.0/src/fuse.min.js"></script>
    <script src="https://screenfeedcontent.blob.core.windows.net/html/airports.js"></script>

</head>

<html>
<body>


<div class="col-md-12 flight-header">
</div>
<div class="row flight-image">
    <div class ="col-md-2"></div>
    <div class="col-md-8">
        <form id="getFlights" enctype="multipart/form-data">
            <div class= "flight-form">
                <h2>Find the best deals!</h2>
                <div class="form-group col-md-3">
                    <label for="usr">From Airport:</label>
                    <input type="text" class="form-control" name="fromAirport" id = "autocomplete">
                </div>
                <div class="form-group col-md-3">
                    <label for="usr">To Airport:</label>
                    <input type="text" class="form-control" name="toAirport" id = "autocomplete2">
                </div>
                <div class="form-group col-md-2">
                    <label for="usr">Number of Adults</label>
                    <input type="number" class="form-control" name="adults">
                </div>
                <div class="form-group col-md-2">
                    <label for="usr">Date:</label>
                    <div class="input-group input-append date" id="datePicker">
                        <input type="text" class="form-control" name="date"/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                    <div class="form-group col-md-2">
                        <label for="usr" style ="visibility: hidden">dd</label>
                    <button type="submit" class="btn btn-info">Search Flights</button>
                        </div>
            </div>
        </form>
    </div>
</div>
<div class="row flight-response">
    <div class ="col-md-2"></div>
    <div class="col-md-8 each-flight" id = "responsecontainer">
        <div id="loading-image" hidden = "true">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
        </div>
    </div>
</body>

<style>
    .autocomplete-wrapper {
        position: relative;

    input {
        width: 100%;
    }
    }
    .autocomplete-results {
        position: relative;
        background: #2b95b1;
        z-index: 1;
        top: 100%;
        left: 0;
        font-size: 13px;
        border: solid 1px #ddd;
        border-top-width: 0;
        border-bottom-color: #ccc;
        box-shadow:
            0 5px 10px rgba(0, 0, 0, 0.2);
    }

    .autocomplete-result {
        padding: 12px 15px;
        border-bottom: solid 1px #eee;
        cursor: pointer;
    }

    .autocomplete-result:last-child {
        border-bottom-width: 0;
    }

    .autocomplete-location {
        opacity: .8;
        font-size: smaller;
    }
</style>


<script>
    $(document).ready(function () {
        $('#datePicker')
            .datepicker({
                format: 'yyyy-mm-dd'
            });
    });
</script>
<script>
    // this is the id of the form
    $("#getFlights").submit(function (e) {

        var url = "/getFlights"; // the script where you handle the form input.
        $('#loading-image').show();
        $.ajax({
            type: "POST",
            url: url,
            data: $("#getFlights").serialize(), // serializes the form's elements.
            success: function (data) {
                $("#responsecontainer").html(data);
            },
            complete: function(){
                $('#loading-image').hide();
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>

<script>
    var options = {
        shouldSort: true,
        threshold: 0.4,
        maxPatternLength: 32,
        keys: [{
            name: 'iata',
            weight: 0.5
        }, {
            name: 'name',
            weight: 0.3
        }, {
            name: 'city',
            weight: 0.2
        }]
    };

    var fuse = new Fuse(airports, options);


    var ac = $('#autocomplete')
        .on('click', function(e) {
            e.stopPropagation();
        })
        .on('focus keyup', search)
        .on('keydown', onKeyDown);

    var wrap = $('<div>')
        .addClass('autocomplete-wrapper')
        .insertBefore(ac)
        .append(ac);

    var list = $('<div>')
        .addClass('autocomplete-results')
        .on('click', '.autocomplete-result', function(e) {
            e.preventDefault();
            e.stopPropagation();
            selectIndex($(this).data('index'));
        })
        .appendTo(wrap);

    $(document)
        .on('mouseover', '.autocomplete-result', function(e) {
            var index = parseInt($(this).data('index'), 10);
            if (!isNaN(index)) {
                list.attr('data-highlight', index);
            }
        })
        .on('click', clearResults);

    function clearResults() {
        results = [];
        numResults = 0;
        list.empty();
    }

    function selectIndex(index) {
        if (results.length >= index + 1) {
            ac.val(results[index].iata);
            clearResults();
        }
    }

    var results = [];
    var numResults = 0;
    var selectedIndex = -1;

    function search(e) {
        if (e.which === 38 || e.which === 13 || e.which === 40) {
            return;
        }

        if (ac.val().length > 0) {
            results = _.take(fuse.search(ac.val()), 5);
            numResults = results.length;

            var divs = results.map(function(r, i) {
                return '<div class="autocomplete-result" data-index="'+ i +'">'
                    + '<div><b>'+ r.iata +'</b> - '+ r.name +'</div>'
                    + '<div class="autocomplete-location">'+ r.city +', '+ r.country +'</div>'
                    + '</div>';
            });

            selectedIndex = -1;
            list.html(divs.join(''))
                .attr('data-highlight', selectedIndex);

        } else {
            numResults = 0;
            list.empty();
        }
    }

    function onKeyDown(e) {
        switch(e.which) {
            case 38: // up
                selectedIndex--;
                if (selectedIndex <= -1) {
                    selectedIndex = -1;
                }
                list.attr('data-highlight', selectedIndex);
                break;
            case 13: // enter
                selectIndex(selectedIndex);
                break;
            case 9: // enter
                selectIndex(selectedIndex);
                e.stopPropagation();
                return;
            case 40: // down
                selectedIndex++;
                if (selectedIndex >= numResults) {
                    selectedIndex = numResults-1;
                }
                list.attr('data-highlight', selectedIndex);
                break;

            default: return; // exit this handler for other keys
        }
        e.stopPropagation();
        e.preventDefault(); // prevent the default action (scroll / move caret)
    }
</script>

</html>