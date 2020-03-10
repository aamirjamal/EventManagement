<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }
    if(isset($_GET['id'])){
        $db = new DB();
        $sessions = $db->getSessionsById($_GET['id']);
        $event = $db->getNameFromId($_GET['id'],'event');

        echo MyUtils::html_header("Registration");
        echo MyUtils::html_navbar();
        echo "<div class='container'>";
        echo "<h3>Sessions for $event</h3>";
        echo MyUtils::html_session_table($sessions,false);
        echo "<br><br>";
        echo "<a href='eventsList.php'>Go back to Events</a>";
        echo "</div>";
        echo MyUtils::html_footer();
    } else {
        echo "No event id passed!";
    }
?>