<?php
class MyUtils{

	static function html_navbar(){
		$string = <<<NAV
		<nav>
		<div class="nav-wrapper teal">
			<ul class="right">
				<li><span>User : {$_SESSION['username']}</span></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
			<ul id="nav-mobile" class="left">
				<li><a href="eventsList.php">Events</a></li>
				<li><a href="registration.php">Registration</a></li>
NAV;
		if($_SESSION['role'] != 3){
			$string .= '<li><a href="adminPage.php">Admin</a></li>';
		}

		$string .= "</ul></div></nav>";
		return $string;
	}

	static function html_event_table_header(){
		$string = <<<TABLE
		<div class="container">
		<h3>Events</h3>
		<a data-target="modal1" class="btnAdd waves-effect waves-light btn-small modal-trigger"
		href="#modal1">Add Event <i class="material-icons right">add</i></a>
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
		return $string;
	}

	static function html_session_table($sessions,$isRegistration){
		$sessionTable = "No Session available for this Event at this moment.";
        if(count($sessions)>0){
        $sessionTable = <<<SESSION
        <table class="striped">
        <thead>
          <tr>
              <th>Session ID</th>
              <th>Session Name</th>
              <th>Start date</th>
			  <th>End date</th>
SESSION;
			if($isRegistration){
				$sessionTable .= "<th>Action</th>";
			}
		$sessionTable .= "</tr></thead><tbody>";
		
        foreach($sessions as $s){
            $startdate = new DateTime($s->getStartDate());
            $startdate = $startdate->format('Y-m-d');
            $enddate = new DateTime($s->getEndDate());
            $enddate = $enddate->format('Y-m-d');
            $sessionTable .= <<<ROW
            <tr>
            <td>{$s->getID()}</td>
            <td>{$s->getName()}</td>
            <td>{$startdate}</td>
			<td>{$enddate}</td>
ROW;
			if($isRegistration){
				$sessionTable .= "<td><a class='btn-small' href='addRegistration.php?sid={$s->getID()}&uid={$_SESSION['userId']}''>Register</a></td>";
			}
            
            $sessionTable .= "</tr>";
        }
        $sessionTable .= "</tbody></table>";
		}
		return $sessionTable;
	}

	static function html_header($title="Untitled",$styles=""){
			$string = <<<END
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>$title</title>
		<link href="$styles" type="text/css" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" 
		integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		<script src="index.js"></script>
		</head>
		<body>
END;
		return $string;
	}

	static function actionButtons($editClass,$deleteid,$page){
		$editBtn = "<a href='#modal1' style='display:inline-block;' data-target='modal2' class='$editClass waves-effect waves-light 
		btn modal-trigger' type='submit' name='action'><i class='material-icons'>edit</i></a>";
		$dltBtn1 = "<form style='display:inline-block;' action='$page' method='post'><button 
		class='waves-effect waves-light btn' name='del' value='";
		$dltBtn2 = "'><i class='material-icons'>delete</i></button></form>";
		return $editBtn." ".$dltBtn1.$deleteid.$dltBtn2;
	}


	static function makeElemetsSelect($id,$elements,$label,$name){
		$sel = "<select class='browser-default selElement' id='$id' name='$name' required><option value='' disabled selected>$label</option>";
		foreach($elements as $k=>$v){
			$sel .= "<option value='$k'>$v</option>";
		}
		$sel .= "</select><br>";
		return $sel;
	}


	static function html_footer($text=""){
		$string ="\n$text\n</body>\n</html>";
		return $string;
	}

	static function sanitize($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

} // end class


?>