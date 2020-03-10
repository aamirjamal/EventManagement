<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();
    $sessions = $db->getSessionsById($_GET["event"]);
    $options = "<option value='' disabled selected>Select Session</option>";
    foreach($sessions as $s){
        $options .=  "<option value='{$s->getId()}'>{$s->getName()}</option>";
    }
    echo $options;
?>


