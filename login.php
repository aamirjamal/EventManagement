<?php
    session_start();
    if(isset($_SESSION['userId'])){ header("location:eventsList.php");}

    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    echo MyUtils::html_header("Login");
    echo <<<LOGIN
    <div class="container teal-text text-darken-1">
    <h2 class="center"> Event Management System </h2>
	<div class="row card hoverable">
		<div class="card-content ">
		<h4 class="center teal-text">Login Form</h4>
        <form class="row s12" action="" method="POST">
        <div class="row">
            <div class="col s6 offset-s3">
                <div class="input-field">
                    <input type="text" name="user" placeholder="Username*" required>
                </div>
            </div>
        </div>
        <div class="row">
			<div class="col s6 offset-s3">
			<div class="input-field">
				<input type="password" name="pass" placeholder="Password*" required>
			</div>
        </div>
        </div>
		<div class="col s12 center">
			<button type="submit" name="login" class="btn btn-large waves-effect waves-light ">Login<i class="material-icons right">send</i></button>
            <br><br>
            <a href="signup.php">Don't have an account? Sign up</a>
        </div>
	</form>
	</div>
</div>
</div>
LOGIN;
    echo MyUtils::html_footer("");

    if(isset($_POST["login"])) {
        $user = $_POST["user"];
        $pass = hash("sha256",$_POST["pass"]);
        $db = new DB();
        $attendee = $db->getAttendee($user, $pass);
        if($attendee){
            $role = $attendee->getRole();
            $_SESSION['role'] = $role; 
            $_SESSION['userId'] = $attendee->getAttendeeId();
            $_SESSION['username'] = $attendee->getAttendeeName();
            header("Location:eventsList.php");
        } else {
             echo "<div class='center red-text'>Invalid username/password!</div>";
        }
    }
?>