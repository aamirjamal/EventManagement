<?php
class Session {
    private $idsession;
    private $name;
    private $startdate;
    private $enddate;
    private $numberallowed;
    private $event;

    public function getId(){
        return $this->idsession;
    }

    public function getName(){
        return $this->name;
    }

    public function getStartDate(){
        return $this->startdate;
    }

    public function getEndDate(){
        return $this->enddate;
    }

    public function getNumAllowed(){
        return $this->numberallowed;
    }

    public function getEvent(){
        return $this->event;
    }
}