<?php
class Attendee {
    private $idattendee;
    private $name;
    private $password;
    private $role;

    public function getAttendeeId(){
        return $this->idattendee;
    }

    public function getAttendeeName(){
        return $this->name;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRole(){
        return $this->role;
    }
}