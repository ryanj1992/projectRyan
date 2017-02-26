<?php
session_start();
include_once 'dbconnect.php';

$id = $_SESSION['userSession'];
$fromAirport = strip_tags($_POST['fromAirport']);
$toAirport = strip_tags($_POST['toAirport']);
$adult = strip_tags($_POST['adults']);
$date = strip_tags($_POST['date']);
$url = "https://www.googleapis.com/qpxExpress/v1/trips/search?key=AIzaSyAP-S9hmsIxeNYegVIH7zz2XbCHzhpuEhs";

$postData = array(
    "request" => array(
        "passengers" => array(
            "adultCount" => $adult
        ),
        "slice" => array(
            array(
                "origin" => "$fromAirport",
                "destination" => "$toAirport",
                "date" => "$date",
            ),
        ),
        "solutions" => "5"
    )
);

$curlConnection = curl_init();

curl_setopt($curlConnection, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($curlConnection, CURLOPT_URL, $url);
curl_setopt($curlConnection, CURLOPT_POST, TRUE);
curl_setopt($curlConnection, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($curlConnection, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, FALSE);

$results = curl_exec($curlConnection);

$json = json_decode($results, true);

$userAdd = array();
$i = 0;

echo "<div>" . "<h1>You're Flight Results</h1>" . "</div>";
foreach ($json['trips']['tripOption'] as $options) {
    $i++;
    echo "<div class ='each-flight2'>" . "Total Price: " . $options['saleTotal'] . "  <div class='btn-group'>
    <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
    Add <span class='caret'></span></button>
    <ul class='dropdown-menu' role='menu'>
      <li><a href='#'>Itinerary</a></li>
    </ul>
  </div></div>";
    $userAdd[$i]['saleTotal'] = $options['saleTotal'];
    foreach ($options['slice'] as $slices) {
        foreach ($slices['segment'] as $segments) {
            foreach ($segments['leg'] as $leg) {
                echo "<div>" . "Departure Time: " . $leg['departureTime'] . "</div>\n";
                $userAdd[$i]['departureTime'] = $leg['departureTime'];
                echo "<div>" . "Arrival Time: " . $leg['arrivalTime'] . "</div>\n";
                $userAdd[$i]['arrivalTime'] = $leg['arrivalTime'];
                echo "<div>" . "Origin: " . $leg['origin'] . "</div>\n";
                $userAdd[$i]['origin'] = $leg['origin'];
                echo "<div>" . "Destination: " . $leg['destination'] . "</div>\n" . "<hr>";
                $userAdd[$i]['destination'] = $leg['destination'];
            }
        }
    }
    echo $userAdd[$i]['saleTotal'];
    echo $userAdd[$i]['origin'];
}

?>