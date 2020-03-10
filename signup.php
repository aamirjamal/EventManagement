<?php
    session_start();
    if(isset($_SESSION['userId'])){ header("location:eventsList.php");}

    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    echo MyUtils::html_header("Sign Up");
?>
    <div class="container teal-text text-darken-1">
    <h2 class="center"> Event Management System </h2>
	<div class="row card hoverable">
		<div class="card-content ">
		<h4 class="center teal-text">Sign up Form</h4>
        <form class="row s12" action="" method="POST">
        <div class="row">
            <div class="col s6 offset-s3">
                <div class="input-field">
                    <input type="text" name="user" placeholder="Enter Username"  
                        pattern=".{3,}" title="3 characters minimum" required>
                </div>
            </div>
        </div>
        <div class="row">
			<div class="col s6 offset-s3">
                <div class="input-field">
                    <input type="password" name="pass1" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" 
                    title="Password must contain at least 6 characters, including letters and numbers." required>
                </div>
            </div>
        </div>
        <div class="row">
			<div class="col s6 offset-s3">
                <div class="input-field">
                    <input type="password" name="pass2" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" 
                    title="Password must contain at least 6 characters, including letters and numbers." required>
                </div>
            </div>
        </div>
		<div class="col s12 center">
			<button type="submit" name="signup" class="btn btn-large waves-effect waves-light ">Sign Up<i class="material-icons right">send</i></button>
            <br><br>
            <a href="login.php">Already have an account? Log in</a>
        </div>
	    </form>
	</div>
</div>
</div>

<?php
    echo MyUtils::html_footer("");

    if(isset($_POST["signup"])) {
        $username = MyUtils::sanitize($_POST["user"]);
        $pass1 = $_POST["pass1"];
        $pass2 = $_POST["pass2"];
        $db = new DB();
        $users = $db->selectAllFromTable("Attendee");        

        if(Validation::validPasswords($pass1,$pass2)
        && Validation::validUserName($username,$users)){
            $db->addAttendee($username,$pass1,3);
            echo "<div class='center teal-text'>User Registered!</div>";
        } else {
            $errMsg = Validation::getError();
             echo "<div class='center red-text' style='white-space: pre'>$errMsg</div>";
        }
    }
?>