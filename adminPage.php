<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    echo MyUtils::html_header("Admin Page");
    echo MyUtils::html_navbar();
?>
<div class="container">
    <h3>Admin Page</h3>
    <div class="collection">
        <?php if($_SESSION['role'] == 1){ ?>
        <a href="userList.php" class="collection-item">User Management</a>
        <a href="venueList.php" class="collection-item">Venue Management</a>
        <?php } ?>
        <a href="eventList.php" class="collection-item">Event Management</a>
        <a href="sessionList.php" class="collection-item">Session Management</a>
        <a href="attendeeList.php" class="collection-item">Attendee Management</a>
    </div>        
</div>

<?php
    echo MyUtils::html_footer("");

?>