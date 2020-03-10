<?php
    session_start();
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    if(isset($_GET['event'])){
        $db = new DB();
        $sessions = $db->getSessionsById($_GET['event']);
        echo MyUtils::html_session_table($sessions,true);
    } else {
        echo "No event id passed!";
    }

?>