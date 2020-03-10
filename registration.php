<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }
    $db = new DB();

    if(isset($_GET['del'])) {
        $db->cancelRegisteredSession($_GET['del']);
    }

    $sessions = $db->getAttendeeSessions($_SESSION["userId"]);

    echo MyUtils::html_header("Registration");
    echo MyUtils::html_navbar();

    $table = <<<TABLE
    <div class="container">
        <h3>Registrations</h3>
        <a href="addRegistration.php" class="btn">Register an event <i class="material-icons">add</i></a>
        <table class="striped">
        <thead>
          <tr>
              <th>Event</th>
              <th>Session Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Cancel</th>
          </tr>
        </thead>
        <tbody>
TABLE;
    foreach($sessions as $s){
        $startdate = new DateTime($s['startdate']);
        $startdate = $startdate->format('Y-m-d');
        $enddate = new DateTime($s['enddate']);
        $enddate = $enddate->format('Y-m-d');
        $table .= <<<ROW
        <tr>
        <td>{$db->getNameFromId($s['event'],"event")}</td>
        <td>{$s['name']}</td>
        <td>$startdate</td>
        <td>$enddate</td>
        <td><a class="btn-small" href="registration.php?del={$s['session']}">
        <i class="material-icons">delete</i>
        </a>
        </tr>
ROW;
    }
    $table .= "</tbody></table></div>";

    echo MyUtils::html_footer($table);
?>