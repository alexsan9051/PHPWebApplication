<?php

require_once("DB.class.php");

if(isset($_POST['action'])){
    if ($_POST['action'] == "viewUsers") { viewUsers(); }
    if ($_POST['action'] == "viewVenue") { viewVenue(); }
    if ($_POST['action'] == "viewEvent") { viewEvent(); viewAttendeeEvents();}
    if ($_POST['action'] == "viewSessions") { viewSessions(); viewAttendeeSessions();}
}

function viewUsers() {
    $db = new DB();
    $string = $db->getAllUsersAsTable();
    echo $string;
}

function viewVenue() {
    $db = new DB();
    $string = $db->getAllVenuesAsTable();
    echo $string;
}

function viewEvent() {
    $db = new DB();
    $string = $db->getAllEventsAsTable();
    echo $string;
}

function viewAttendeeEvents() {
    $db = new DB();
    $string = $db->getAllAttendeeEventsAsTable();
    echo $string;
}

function viewSessions() {
    $db = new DB();
    $string = $db->getAllSessionsAsTable();
    echo $string;
}

function viewAttendeeSessions() {
    $db = new DB();
    $string = $db->getAllAttendeeSessionsAsTable();
    echo $string;
}

?>