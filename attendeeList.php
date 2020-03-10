 <?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();

    if(isset($_POST['session'])){   // add or update
        // no need for sanitization & validation as all from <select>
        if(isset($_POST['id'])){
            $db->updateAttendeeSession($_POST['id'],$_POST['user'],$_POST['session']);
        } else {
            $db->addAttendeeSession($_POST['user'],$_POST['session'],$_POST['event']);
        }
    } elseif (isset($_POST['delsession'])){
        $db->deleteAttendeeSession($_POST['user'],$_POST['delsession'],$_POST['event']);
    }

    if($_SESSION['role'] == 1){
        $attendees = $db->getAttendeeList();
        $events = $db->selectAllFromTable("Event");
    } else {
        $attendees = $db->getAttendeeListOfManager($_SESSION['userId']);
        $events = $db->getEventsOfManager($_SESSION['userId']);
    }

    $users = $db->selectAllFromTable("Attendee");

    $userLists = [];
    foreach($users as $u){
        $userLists[$u->getAttendeeId()] = $u->getAttendeeName();
    }
    $eventsLists = [];
    foreach($events as $e){
        $eventsLists[$e->getId()] = $e->getName();
    }

    echo MyUtils::html_header("Attendees");
    echo MyUtils::html_navbar();
?>

<div class="container">
<h3>Attendees</h3>
<a data-target="modal1" class="btnAdd waves-effect waves-light btn-small modal-trigger"
href="#modal1">Add Attendee <i class="material-icons right">add</i></a>
<table class="striped centered">
    <thead>
      <tr>
          <th style="display:none;">AttendeeID</th>
          <th>Attendee</th>
          <th style="display:none;">SessionID</th>
          <th>Session</th>
          <th style="display:none;">EventID</th>
          <th>Event</th>
      </tr>
    </thead>
    <tbody>

<?php
    $dltBtn1 = "<form action='attendeeList.php' method='post'>
    <input type='hidden' name='delsession' value='";
    $dltattendee="'><input type='hidden' name='user' value='";
    $dltevent="'><button class='waves-effect waves-light btn' name='event' value='";
    $dltBtn2 = "'><i class='material-icons'>delete</i></button></form>";

    $str = "";
    foreach($attendees as $a){
        $str .= <<<ROW
        <tr>
        <td style="display:none;">{$a['AttendeeID']}</td>
        <td>{$a['Attendee']}</td>
        <td style="display:none;">{$a['idsession']}</td>
        <td>{$a['Session']}</td>
        <td style="display:none;">{$a['idevent']}</td>
        <td>{$a['Event']}</td>
        <td>$dltBtn1{$a['idsession']}$dltattendee{$a['AttendeeID']}{$dltevent}{$a['idevent']}$dltBtn2</td>
        </tr>
ROW;
    }
    $str .= "</tbody></table></div>";
?>


<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="container">
            <form id="formAttendeeAdd" action="attendeeList.php" method="post">
                <div class="row">
                <?php echo MyUtils::makeElemetsSelect("addUser",$userLists,"Select User","user"); ?>
                </div> 
                <div class="row">
                <?php echo MyUtils::makeElemetsSelect("addEventSel",$eventsLists,"Select Event","event"); ?>
                </div>
                <div class="row">
                <select class='browser-default selElement' id='addSessionSel' name='session' required>
                    <option value='' disabled selected>Select Session</option>
                </select>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createattendee">Add
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal2">
    <div class="modal-content">
        <div class="container">
            <form id="formattendeeEdit" action="attendeeList.php">
                <input type="number" name="id" id="attendeeEditId" style="display:none;">
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="attendeeEditName" name="name" type="text" required>
                    <label for="attendeeEditName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <label for="attendeeEditsd">Start Date</label>
                        <input id="attendeeEditsd" type="date" placeholder="." class="datepicker" name="start" required>   
                    </div>  
                    <div class="input-field col s4 offset-s2">
                        <input id="attendeeEdited" type="date" placeholder="." class="datepicker" name="end" required>
                        <label for="attendeeEdited">End Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="attendeeEditAllowed" name="allowed" type="number" 
                    min="10" title="Minimum allowed is 10!" required>
                    <label for="attendeeEditAllowed">Nums Allowed</label>
                    </div>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createattendee">Edit
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php echo MyUtils::html_footer($str); ?>