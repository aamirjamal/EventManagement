<?php
class DB {
    private $dbh;   // db handle
    function __construct(){
        try{
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}", 
        $_SERVER['DB_USER'],$_SERVER['DB_PASSWORD']);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die("Bad database connection");
        }
    }

    /**
     * Selects all the rows of a given table.
     * @param : $tableName - name of the table
     */
    function selectAllFromTable($tableName){
        try{
            $data = [];
            include "{$tableName}.class.php";
            $tname = strtolower($tableName);
            $stmt = $this->dbh->prepare("SELECT * FROM $tname");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,$tableName);
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

//////////////////////// VENUE BEGIN ////////////////////////

    function updateVenue($id,$name,$capacity){
        try{
            $stmt = $this->dbh->prepare("UPDATE venue SET name = '$name', capacity = '$capacity' WHERE idvenue = '$id'");
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function addVenue($name,$capacity){
        try{
            $stmt = $this->dbh->prepare("INSERT INTO venue (name,capacity) VALUES (:name,:capacity)");
            $stmt->execute(['name'=>$name,'capacity'=>$capacity]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function deleteVenue($id){
        try{
            $stmt = $this->dbh->prepare("DELETE FROM venue where idvenue = :id");
            $stmt->execute(['id'=>$id]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

//////////////////////// VENUE END ////////////////////////


//////////////////////// USER BEGIN ////////////////////////


function updateAttendee($id,$name,$password,$role){
    try{
        // if password is hashed, don't update it
        if (preg_match("/^([a-f0-9]{64})$/", $password) == 1) {     
            $stmt = $this->dbh->prepare("UPDATE attendee SET name = '$name', role = '$role' WHERE idattendee = '$id'");
         } else {
            $password = hash('sha256',$password);
            $stmt = $this->dbh->prepare("UPDATE attendee SET name = '$name', password = '$password', role = '$role' WHERE idattendee = '$id'");
         }
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addAttendee($name,$password,$role){
    try{
        $password = hash('sha256',$password);
        $stmt = $this->dbh->prepare("INSERT INTO attendee (name,password,role) VALUES (:name,:password,:role)");
        $stmt->execute(['name'=>$name,'password'=>$password,'role'=>$role]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function deleteAttendee($id){
    try{
        $stmt = $this->dbh->prepare("DELETE FROM attendee where idattendee = :id");
        $stmt->execute(['id'=>$id]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

//////////////////////// USER END ////////////////////////

//////////////////////// EVENT BEGIN ////////////////////////

function updateEvent($id,$name,$datestart,$dateend,$numberallowed,$venue){
    try{
        $stmt = $this->dbh->prepare("UPDATE event SET name = '$name', datestart = '$datestart', 
        dateend = '$dateend', numberallowed = '$numberallowed', venue = '$venue' WHERE idevent = '$id'");
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addEvent($name,$datestart,$dateend,$numberallowed,$venue){
    try{
        $stmt = $this->dbh->prepare("INSERT INTO event (name,datestart,dateend,numberallowed,venue) VALUES (:name,:datestart,:dateend,:numberallowed,:venue)");
        $stmt->execute(['name'=>$name,'datestart'=>$datestart,'dateend'=>$dateend,'numberallowed'=>$numberallowed,'venue'=>$venue]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function deleteEvent($id){
    try{
        $stmt = $this->dbh->prepare("DELETE FROM event where idevent = :id");
        $stmt->execute(['id'=>$id]);
        $stmt = $this->dbh->prepare("DELETE FROM manager_event where event = :id");
        $stmt->execute(['id'=>$id]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getEventIDFromName($name){
    try { 
        $stmt = $this->dbh->prepare("SELECT idevent FROM event WHERE name = :name");
        $stmt->execute(['name'=>$name]);
        return $stmt->fetch()[0];

    } catch (PDOException $e) {
        echo $e->getMessage();
        return null;
    }
}
//////////////////////// EVENT END ////////////////////////

//////////////////////// SESSION BEGIN ////////////////////////

function updateSession($id,$name,$startdate,$enddate,$numberallowed,$event){
    try{
        $stmt = $this->dbh->prepare("UPDATE session SET name = '$name', startdate = '$startdate', 
        enddate = '$enddate', numberallowed = '$numberallowed', event = '$event' WHERE idsession = '$id'");
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function addSession($name,$startdate,$enddate,$numberallowed,$event){
    try{
        $stmt = $this->dbh->prepare("INSERT INTO session (name,startdate,enddate,numberallowed,event) 
            VALUES (:name,:startdate,:enddate,:numberallowed,:event)");
        $stmt->execute(['name'=>$name,'startdate'=>$startdate,'enddate'=>$enddate,'numberallowed'=>$numberallowed,'event'=>$event]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function deleteSession($id){
    try{
        $stmt = $this->dbh->prepare("DELETE FROM session where idsession = :id");
        $stmt->execute(['id'=>$id]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
//////////////////////// SESSION END ////////////////////////

//////////////////////// ATTENDEE BEGIN ////////////////////////

function getAttendeeList(){
    try{
        $sql = "SELECT a.idattendee as AttendeeID, a.name as Attendee,
        s.idsession, s.name as Session, e.name as Event, e.idevent as idevent
        FROM event as e
        INNER JOIN session as s ON e.idevent = s.event
        INNER JOIN attendee_session as m ON m.session = s.idsession
        INNER JOIN attendee as a ON a.idattendee = m.attendee
        INNER JOIN role as r ON r.idrole = a.role";
        $data = [];
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while($row=$stmt->fetch()){
            $data[] = $row;
        }
        return $data;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return [];
    }
}

function addAttendeeSession($user,$session,$event){
    try{
        $stmt = $this->dbh->prepare("REPLACE INTO attendee_session (session,attendee) VALUES (:session,:user)");
        $stmt->execute(['session'=>$session,'user'=>$user]);
        $stmt = $this->dbh->prepare("REPLACE INTO attendee_event (event,attendee) VALUES (:event,:user)");
        $stmt->execute(['event'=>$event,'user'=>$user]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function deleteAttendeeSession($user,$session,$event) {
    try{
        $stmt = $this->dbh->prepare("DELETE FROM attendee_session where session = :session AND attendee = :user");
        $stmt->execute(['session'=>$session, 'user'=>$user]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

//////////////////////// ATTENDEE END ////////////////////////

//////////////////////// MANAGER BEGIN ////////////////////////

function addManager($eid,$mid){
    try{
        $stmt = $this->dbh->prepare("INSERT INTO manager_event (event,manager) VALUES (:eid,:mid)");
        $stmt->execute(['eid'=>$eid,'mid'=>$mid]);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
//////////////////////// MANAGER END ////////////////////////


    function getSessionsById($id) {
        try{
            $data = [];
            include "Session.class.php";
            $stmt = $this->dbh->prepare("SELECT * FROM session where event = :id");
            $stmt->execute(['id'=>$id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Session");
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getEventFromSession($sid){
        try{
            $stmt = $this->dbh->prepare("SELECT event FROM session WHERE idsession = :sid");
            $stmt->execute(['sid'=>$sid]);
            return $stmt->fetch()[0];
        }  catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    function registerSession($sid,$uid){
        try {
            $stmt = $this->dbh->prepare("INSERT INTO attendee_session (session,attendee) VALUES (:sid,:uid)");
            $stmt->execute(['sid'=>$sid,'uid'=>$uid]);
            $eid = $this->getEventFromSession($sid);
            $stmt = $this->dbh->prepare("INSERT INTO attendee_event (event,attendee,paid) VALUES (:eid,:uid,0)");
            $stmt->execute(['eid'=>$eid,'uid'=>$uid]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function cancelRegisteredSession($id){
        try{
            $stmt = $this->dbh->prepare("DELETE FROM attendee_session where session = :id and attendee = {$_SESSION['userId']}");
            $stmt->execute(['id'=>$id]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        // TODO
        // if last session of event, remove attendee event
    }

    function getNameFromId($id,$table){
        try{
            $stmt = $this->dbh->prepare("SELECT name FROM $table WHERE id$table = :id");
            $stmt->execute(['id'=>$id]);
            return $stmt->fetch()[0];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }


    function getAttendeeSessions($id){
        try{
            $data = [];
            $stmt = $this->dbh->prepare("SELECT session,event,name,startdate,enddate FROM attendee_session join session on idsession = session where attendee = :id");
            $stmt->execute(['id'=>$id]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getAttendeeEvents($id){
        try{
            $data = [];
            $stmt = $this->dbh->prepare("SELECT event,name FROM attendee_event join event on idevent = event where attendee = :id");
            $stmt->execute(['id'=>$id]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getAllManagers(){
        try{
            $data = [];
            $stmt = $this->dbh->prepare("SELECT idattendee,name FROM attendee WHERE role = 2");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getAttendee($user, $pass){
        try{
            $data = null;
            include "Attendee.class.php";
            $stmt = $this->dbh->prepare("SELECT * FROM attendee WHERE name = :user AND password = :pass");
            $stmt->execute(array('user'=>$user, 'pass'=>$pass));
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Attendee");
            $data = $stmt->fetch();
            return $data;

        } catch (PDOException $e) {
            echo "There was something wrong in accessing the DB : ".$e->getMessage();
            return null;
        }
    }

    //////////////////// MANAGER START ////////////////////

    function getEventsOfManager($mid){
        try {
            $data = [];
            include "Event.class.php";
            $stmt = $this->dbh->prepare("SELECT idevent,name,datestart,dateend,numberallowed,venue from event join manager_event on idevent = event where manager = :mid");
            $stmt->execute(['mid'=>$mid]);
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Event");
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getSessionsOfManager($mid){
        try {
            $data = [];
            include "Session.class.php";
            $sql = "SELECT s.idsession, s.name, s.numberallowed,s.event,s.startdate, s.enddate 
                    FROM event as e JOIN session as s ON e.idevent = s.event
                    JOIN manager_event as m ON e.idevent = m.event WHERE m.manager = :mid";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(['mid'=>$mid]);
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Session");
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    function getAttendeeListOfManager($mid){
        try{
            $sql = "SELECT a.idattendee as AttendeeID, a.name as Attendee,
            s.idsession, s.name as Session, e.name as Event, e.idevent as idevent
            FROM event as e
            JOIN session as s ON e.idevent = s.event
            JOIN manager_event as me ON e.idevent = me.event
            JOIN attendee_session as m ON m.session = s.idsession
            JOIN attendee as a ON a.idattendee = m.attendee
            JOIN role as r ON r.idrole = a.role
            AND me.manager = :mid;";
            $data = [];
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute(['mid'=>$mid]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($row=$stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    //////////////////// MANAGER END ////////////////////
}