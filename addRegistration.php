<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }
    $db = new DB();
    $events = $db->selectAllFromTable("Event");

    if(!empty($_GET["sid"])){
        $sessions = $db->getAttendeeSessions($_SESSION["userId"]);
        $events = $db->getAttendeeEvents($_SESSION["userId"]);
        $sessionExists = false;
        foreach($sessions as $s){
            if($s['session'] == $_GET["sid"]){
                // $event = $s['event'];
                $sessionExists = true;
            break;
            }
        }
        if(!$sessionExists)
            $db->registerSession($_GET["sid"],$_SESSION['userId']);
        header("location:registration.php");
    }

    echo MyUtils::html_header("Register Session");
    echo MyUtils::html_navbar();
?>

    <div class="container">
        <h3>Register a session</h3>
        <div class="row">
            <div class="input-field col s5">
            <select id="selEvent" class="browser-default" onchange="showSessions(this.value)">
                <option value="" disabled selected>Select Event</option>
                <?php
                    foreach($events as $e){
                        echo "<option value='{$e->getId()}'>{$e->getName()}</option>";
                    }
                ?>
            </select>
            </div>
        </div>
    </div>

<?php 
    echo MyUtils::html_footer("<div id='sessionTab' class='container'></div>"); 

?>
