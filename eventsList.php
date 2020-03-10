<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();

    $events = $db->selectAllFromTable("Event");

    echo MyUtils::html_header("Events");
    echo MyUtils::html_navbar();
    $eventlist = <<<TABLE
    <div class="container">
		<h3>Events</h3>
		<table class="striped centered">
			<thead>
			  <tr>
				  <th style="display:none;">ID</th>
				  <th>Event Name</th>
				  <th>Start Date</th>
				  <th>End Date</th>
				  <th>Number Allowed</th>
				  <th>Venue</th>
			  </tr>
			</thead>
			<tbody>
TABLE;
    foreach($events as $e){
        $startdate = new DateTime($e->getStartDate());
        $startdate = $startdate->format('Y-m-d');
        $enddate = new DateTime($e->getEndDate());
        $enddate = $enddate->format('Y-m-d');
        $id = $e->getId();
        $eventlist .= <<<ROW
        <tr>
        <td>{$e->getName()}</td>
        <td>{$startdate}</td>
        <td>{$enddate}</td>
        <td>{$e->getNumAllowed()}</td>
        <td>{$db->getNameFromId($e->getVenue(),"venue")}</td>
        <td><a href='sessionDetails?id=$id'>Session Details</a></td>
        </tr>
ROW;
    }
    $eventlist .= "</tbody></table></div>";

    echo MyUtils::html_footer($eventlist);

?>