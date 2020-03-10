 <?php
    session_start();
    if(!isset($_SESSION['userId'])){ header("location:login.php");}
    function __autoload($class_name) {
        require_once "./classes/$class_name.class.php";
    }

    $db = new DB();

    if(isset($_POST['name'])){       // update or insert
        $name = MyUtils::sanitize($_POST['name']);
        $capacity = MyUtils::sanitize($_POST['capacity']);
        if(Validation::isValidName($name) && Validation::isValidCapacity($capacity)){
            if(isset($_POST['id'])){     // update
                $db->updateVenue($_POST['id'], $name, $capacity);
            } else {                    // insert
                $db->addVenue($name, $capacity);
            }
        } else {
            $err = Validation::getError();
            echo "Validation : ".$err;
        }
    } elseif(isset($_POST['del'])){   // delete
        $db->deleteVenue($_POST['del']);
    }
    $venues = $db->selectAllFromTable("Venue");

    echo MyUtils::html_header("Venues");
    echo MyUtils::html_navbar();
?>
    
    <div class="container">
    <h3>Venues</h3>
    <a data-target="modal1" class="btnAdd waves-effect waves-light btn-small modal-trigger"
    href="#modal1">Add Venue <i class="material-icons right">add</i></a>
    <table class="striped centered">
        <thead>
          <tr>
              <th style="display:none;">ID</th>
              <th>Venue Name</th>
              <th>Capacity</th>
              <th>Action</th>
          </tr>
        </thead>
        <tbody>
<?php

    $str = "";
    foreach($venues as $v){
        $actionBtns = MyUtils::actionButtons('btnEditVenue',$v->getId(),'venueList.php');
        $str .= <<<ROW
        <tr>
        <td style="display:none;">{$v->getId()}</td>
        <td>{$v->getName()}</td>
        <td>{$v->getCapacity()}</td>
        <td> $actionBtns</td>
        </tr>
ROW;
    }
    $str .= "</tbody></table></div>";
?>

<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="container">
            <form id="formVenueAdd" action="venueList.php" method="post">
                <div class="row">
                    <div class="input-field col s12">
                    <input id="venueAddName" type="text" name="name" pattern=".{3,}" required title="3 characters minimum">
                    <label for="venueAddName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input id="venueAddCapacity" min="15" type="number" name="capacity"
                    title="Capacity can't go below 15!" required>
                    <label for="venueAddCapacity">Capacity</label>
                    </div>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createVenue">Add
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal2">
    <div class="modal-content">
        <div class="container">
            <form id="formVenueEdit" action="venueList.php" method="post">
                <input type="number" name="id" id="venueEditId" style="display:none;">
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="venueEditName" name="name" type="text" pattern=".{3,}" required title="3 characters minimum">
                    <label for="venueEditName">Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                    <input placeholder="." id="venueEditCapacity" min="15" name="capacity" type="number"
                    title="Capacity can't go below 15!" required>
                    <label for="venueEditCapacity">Capacity</label>
                    </div>
                </div>
                <button class="waves-effect waves-light btn"
                type="submit" name="createVenue">Edit
                <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>
</div>

<?php
    echo MyUtils::html_footer($str);
?>