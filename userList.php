 
<?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();
    if(isset($_POST['name'])){       // update or insert
        $name = MyUtils::sanitize($_POST['name']);
        $password = MyUtils::sanitize($_POST['password']);
        $role = MyUtils::sanitize($_POST['role']);
        if(Validation::isValidPassword($password) && Validation::isValidName($name)){
            if(isset($_POST['id'])){     // update
                $db->updateAttendee($_POST['id'], $name, $password, $role);
            } else {                    // insert
                $db->addAttendee($name, $password, $role);
            }
        } else {
            $err = Validation::getError();
            echo "Validation : ".$err;
        }
    } elseif(isset($_POST['del'])){   // delete
        $db->deleteAttendee($_POST['del']);
    }
    $user = $db->selectAllFromTable("Attendee");

    echo MyUtils::html_header("Users");
    echo MyUtils::html_navbar();
?>
    
    <div class="container">
    <h3>Users</h3>
    <a data-target="modal1" class="btnAdd waves-effect waves-light btn-small modal-trigger"
    href="#modal1">Add User <i class="material-icons right">add</i></a>
    <table class="striped centered">
        <thead>
          <tr>
              <th style="display:none;">ID</th>
              <th>Name</th>
              <th>Password</th>
              <th>Role</th>
              <th>Action</th>
          </tr>
        </thead>
        <tbody>
<?php
    $editBtn = "<a href='#modal1' data-target='modal2' class='btnEditUser waves-effect waves-light 
    btn modal-trigger' type='submit' name='action'><i class='material-icons'>edit</i></a>";
    $dltBtn1 = "<form style='display:inline-block;' action='userList.php' method='post'><button 
    class='waves-effect waves-light btn' name='del' value='";
    $dltBtn2 = "'><i class='material-icons'>delete</i></button></form>";

    $str = "";
    foreach($user as $v){
        $id = $v->getAttendeeId();
        $action = "";
        if($id==1){
            $action .= $editBtn;
        } else {
            $action .= $editBtn." ".$dltBtn1.$id.$dltBtn2;
        }
        $str .= <<<ROW
        <tr>
        <td style="display:none;">{$id}</td>
        <td>{$v->getAttendeeName()}</td>
        <td>{$v->getPassword()}</td>
        <td>{$v->getRole()}</td>
        <td>$action</td>
        </tr>
ROW;
    }
    $str .= "</tbody></table></div>";
?>

<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="container">
            <form id="formAddUser" action="userList.php" method="POST">
                <div class="row">
                    <div class="input-field col s12">
                    <input id="userAddName" type="text" name="name" required>
                    <label for="userAddName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input id="userAddPassword" type="password" name="password" pattern="(?=.*\d)(?=.*[a-z]).{6,}" required
                    title="Password must contain at least 6 characters, including letters and numbers.">
                    <label for="userAddPassword">Password</label>
                    </div>
                </div>
                <div class="row">
                <select class="browser-default" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createUser">Add
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal2">
    <div class="modal-content">
        <div class="container">
            <form id="formUserEdit" action="userList.php" method="post">
                <input type="number" name="id" id="userEditId" style="display:none;">
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="userEditName" name="name" type="text" required>
                    <label for="userEditName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="userEditPassword" name="password" pattern="(?=.*\d)(?=.*[a-z]).{6,}"
                    title="Password must contain at least 6 characters, including letters and numbers." type="password" required>
                    <label for="userEditPassword">Password</label>
                    </div>
                </div>
                <div class="row">
                <select class="browser-default" name="role" id="userEditRole" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createUser">Edit
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php
    echo MyUtils::html_footer($str);
?>