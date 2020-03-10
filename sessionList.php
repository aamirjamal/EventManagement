 <?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();

    if(isset($_POST['name'])){       // update or insert
        $name = MyUtils::sanitize($_POST['name']);
        $startdate = MyUtils::sanitize($_POST['start']);
        $enddate = MyUtils::sanitize($_POST['end']);
        $allowed = MyUtils::sanitize($_POST['allowed']);    
        $event = MyUtils::sanitize($_POST['event']);    
        if(Validation::isValidName($name) && Validation::isValidAllowedNum($allowed
        && Validation::areValidDates($startdate,$enddate))){
            if(isset($_POST['id'])){     // update
                $db->updateSession($_POST['id'], $name, $startdate, $enddate, $allowed, $event);
            } else {                    // insert
                $db->addSession($name, $startdate, $enddate, $allowed, $event);
            }
        } else {
            $err = Validation::getError();
            echo "Validation : ".$err;
        }
    } elseif(isset($_POST['del'])){   // delete
        $db->deleteSession($_POST['del']);
    }
    if($_SESSION['role'] == 1){
        $sessions = $db->selectAllFromTable("Session");
        $events = $db->selectAllFromTable("Event");
    } else {
        $sessions = $db->getSessionsOfManager($_SESSION['userId']);
        $events = $db->getEventsOfManager($_SESSION['userId']);
    }

    $eventLists = [];
    foreach($events as $v){
        $eventLists[$v->getId()] = $v->getName();
    }
    function makeEventSelect($db,$id,$events){
        $sel = "<select class='browser-default selSessionEvent' id='$id' name='event' required><option value='' disabled selected>Select event</option>";
        foreach($events as $k=>$v){
            $sel .= "<option value='$k'>$v</option>";
        }
        $sel .= "</select><br>";
        return $sel;
    }

    echo MyUtils::html_header("Sessions");
    echo MyUtils::html_navbar();
?>
    
    <div class="container">
    <h3>Sessions</h3>
    <a data-target="modal1" class="btnAdd waves-effect waves-light btn-small modal-trigger"
    href="#modal1">Add Session <i class="material-icons right">add</i></a>
    <table class="striped centered">
        <thead>
          <tr>
              <th style="display:none;">ID</th>
              <th>Session Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Number Allowed</th>
              <th>Event</th>
          </tr>
        </thead>
        <tbody>
<?php

    $str = "";
    foreach($sessions as $v){
        $actionBtns = MyUtils::actionButtons('btnEditSession',$v->getId(),'sessionList.php');
        $startdate = new DateTime($v->getStartDate());
        $startdate = $startdate->format('Y-m-d');
        $enddate = new DateTime($v->getEndDate());
        $enddate = $enddate->format('Y-m-d');
        $str .= <<<ROW
        <tr>
        <td style="display:none;">{$v->getId()}</td>
        <td>{$v->getName()}</td>
        <td>{$startdate}</td>
        <td>{$enddate}</td>
        <td>{$v->getNumAllowed()}</td>
        <td>{$db->getNameFromId($v->getEvent(),"event")}</td>
        <td>$actionBtns</td>
        </tr>
ROW;
    }
    $str .= "</tbody></table></div>";
?>

<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="container">
            <form id="formSessionAdd" action="sessionList.php" method='post'>
                <div class="row">
                    <div class="input-field col s12">
                    <input id="sessionAddName" type="text" name="name"  pattern=".{3,}" required title="3 characters minimum">
                    <label for="sessionAddName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <label for="sd">Start Date</label>
                        <input id="sd" type="date" placeholder="." class="datepicker" name="start" required>   
                    </div>  
                    <div class="input-field col s4 offset-s2">
                        <input id="ed" type="date" placeholder="." class="datepicker" name="end" required>
                        <label for="ed">End Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input id="sessionAddNumAllowed" type="number" name="allowed" 
                    min="10" title="Minimum allowed is 10!" required>
                    <label for="sessionAddNumAllowed">Number Allowed</label>
                    </div>
                </div>
                <?php echo makeEventSelect($db,"addSessionEvent",$eventLists); ?>
                <button class="waves-effect waves-light btn"
                type="submit" name="createSession">Add
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal2">
    <div class="modal-content">
        <div class="container">
            <form id="formSessionEdit" action="sessionList.php" method='post'>
                <input type="number" name="id" id="sessionEditId" style="display:none;">
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="sessionEditName" name="name" type="text"  pattern=".{3,}" required title="3 characters minimum">
                    <label for="sessionEditName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <label for="sessionEditsd">Start Date</label>
                        <input id="sessionEditsd" type="date" placeholder="." class="datepicker" name="start" required>   
                    </div>  
                    <div class="input-field col s4 offset-s2">
                        <input id="sessionEdited" type="date" placeholder="." class="datepicker" name="end" required>
                        <label for="sessionEdited">End Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="sessionEditAllowed" name="allowed" type="number" 
                    min="10" title="Minimum allowed is 10!" required>
                    <label for="sessionEditAllowed">Nums Allowed</label>
                    </div>
                </div>
                <?php echo makeEventSelect($db,"sessionEditEvent",$eventLists); ?>
                <button class="waves-effect waves-light btn"
                type="submit" name="createSession">Edit
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php
    echo MyUtils::html_footer($str);
?>