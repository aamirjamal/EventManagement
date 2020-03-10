<?php
class Event {
    private $idevent;
    private $name;
    private $datestart;
    private $dateend;
    private $numberallowed;
    private $venue;

    public function getId(){
        return $this->idevent;
    }

    public function getName(){
        return $this->name;
    }

    public function getStartDate(){
        return $this->datestart;
    }

    public function getEndDate(){
        return $this->dateend;
    }

    public function getNumAllowed(){
        return $this->numberallowed;
    }

    public function getVenue(){
        return $this->venue;
    }
}