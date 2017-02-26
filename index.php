<?php

define('INCLUDE_DIR', dirname(__FILE__) . '/inc/');
$rules = array(
    //
    //main pages
    //
    'expenses' => "/expenses",
    'newLocation' => "/newLocation",
    'holiday' => "/holiday/(?'travel_id'[\w\-]+)",
    'addTravel' => "/addTravel",
    'addExpense' => "/addExpense",
    'getExpenses' => "/getExpenses/(?'travel_id'[\w\-]+)",
    'totalSpent' => "/totalSpent/(?'travel_id'[\w\-]+)",
    'deleteExpense' => "/deleteExpense",
    'pieChart' => "/pieChart/(?'travel_id'[\w\-]+)",
    'map' => "/map",
    'getFlights' => "/getFlights",
    'showFlights' => "/showFlights",
    'register' => "/register",
    //
    //Admin Pages
    //
    'login' => "/login",
    'logout' => "/logout",
    //
    // Home Page
    //
    'home' => "/"
);
$uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/');
$uri = '/' . trim(str_replace($uri,
        ''
        , $_SERVER['REQUEST_URI']), '/');
$uri = urldecode($uri);
foreach ($rules as $action => $rule) {
    if (preg_match('~^' . $rule . '$~i', $uri, $params)) {
        include(INCLUDE_DIR . $action . '.php');
        exit();
    }
}
// nothing is found so handle the 404 error
include(INCLUDE_DIR . '404.php');


?>