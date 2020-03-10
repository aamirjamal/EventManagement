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
        $venue = MyUtils::sanitize($_POST['venue']);  
        if(Validation::isValidName($name) && Validation::isValidAllowedNum($allowed) 
            && Validation::areValidDates($startdate,$enddate)){  
        if(isset($_POST['id'])){     // update
                $db->updateEvent($_POST['id'], $name, $startdate, $enddate, $allowed, $venue);
            } else {                    // insert
                $db->addEvent($name, $startdate, $enddate, $allowed, $venue);
                if(!empty($_POST['manager'])){
                    $id = $db->getEventIDFromName($name);
                    echo "Adding manager {$_POST['manager']} for event $id ";
                    $db->addManager($id,$_POST['manager']);
                }
            }
        } else {
            $err = Validation::getError();
            echo "Validation : ".$err;
        }
    } elseif(isset($_POST['del'])){   // delete
        $db->deleteEvent($_POST['del']);
    }

    if($_SESSION['role'] == 1){
        $events = $db->selectAllFromTable("Event");
    } else {
        $events = $db->getEventsOfManager($_SESSION['userId']);
    }

    $venues = $db->selectAllFromTable("Venue");
    $managers = $db->getAllManagers();
    $venueLists = [];
    foreach($venues as $v){
        $venueLists[$v->getId()] = $v->getName();
    }
    function makeManagerSelect($id,$managers){
        $sel = "<select class='browser-default' id='$id' name='manager'><option value='' disabled selected>Select Manager (optional)</option>";
        foreach($managers as $m){
            $sel .= "<option value='{$m['idattendee']}'>{$m['name']}</option>";
        }
        $sel .= "</select><br>";
        return $sel;
    }

    echo MyUtils::html_header("Events");
    echo MyUtils::html_navbar();
    echo MyUtils::html_event_table_header();

    $str = "";
    foreach($events as $v){
        $actionBtns = MyUtils::actionButtons('btnEditEvent',$v->getId(),'eventList.php');
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
        <td>{$db->getNameFromId($v->getVenue(),"venue")}</td>
        <td>$actionBtns</td>
        </tr>
ROW;
    }
    $str .= "</tbody></table></div>";
?>

<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="container">
            <form id="formEventAdd" action="eventList.php" method='post'>
                <div class="row">
                    <div class="input-field col s12">
                    <input id="eventAddName" type="text" name="name"  pattern=".{3,}" required title="3 characters minimum">
                    <label for="eventAddName">Name</label>
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
                    <input id="eventAddNumAllowed" type="number" min="10" title="No less than 10 allowed!" name="allowed" required>
                    <label for="eventAddNumAllowed">Number Allowed</label>
                    </div>
                </div>
                <?php 
                    echo MyUtils::makeElemetsSelect("addEventVenue",$venueLists,"Select Venue","venue"); 
                    if($_SESSION['role']==1){
                        echo makeManagerSelect("addEventManager",$managers); 
                    } else {
                        $sel = <<<SEL
                        <select class='browser-default' id='addEventManager' name='manager'>
                            <option value='{$_SESSION['userId']}' selected>{$_SESSION['username']}</option>
                        </select>
SEL;
                        echo $sel;
                    }
                ?>
                <button class="waves-effect waves-light btn"
                type="submit" name="createEvent">Add
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal2">
    <div class="modal-content">
        <div class="container">
            <form id="formEventEdit" action="eventList.php" method='post'>
                <input type="number" name="id" id="eventEditId" style="display:none;">
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="eventEditName" name="name" type="text"  pattern=".{3,}" required title="3 characters minimum">
                    <label for="eventEditName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <label for="eventEditsd">Start Date</label>
                        <input id="eventEditsd" type="date" placeholder="." class="datepicker" name="start" required>   
                    </div>  
                    <div class="input-field col s4 offset-s2">
                        <input id="eventEdited" type="date" placeholder="." class="datepicker" name="end" required>
                        <label for="eventEdited">End Date</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="eventEditAllowed" name="allowed" type="number"
                    min="10" title="No less than 10 allowed!" required>
                    <label for="eventEditAllowed">Nums Allowed</label>
                    </div>
                </div>
                <?php echo MyUtils::makeElemetsSelect("eventEditVenue",$venueLists,"Select Venue","venue"); ?>
                <button class="waves-effect waves-light btn"
                type="submit" name="createEvent">Edit
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php
    echo MyUtils::html_footer($str);
?>