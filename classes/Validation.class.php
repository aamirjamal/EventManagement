<?php
    class Validation{
        private static $errMsg = "";
        
        static function getError(){
            return Validation::$errMsg;
        }

        static function validPasswords($pass1,$pass2){
            if($pass1 != $pass2){
                Validation::$errMsg .= "Both the passwords should be matching.\n";
                return false;
            }
            return self::isValidPassword($pass1);
        }

        static function validUserName($username,$users){
            foreach($users as $user){
                if($user->getAttendeeName() == $username){
                    Validation::$errMsg .= "Sorry, this username is already taken.\n";
                    return false;
                }
            }
            return true;
        }

        static function isValidPassword($password){
            if(strlen($password) < 6 || !preg_match("#[0-9]+#", $password) 
                || !preg_match("#[a-z]+#", $password))  { 
                    Validation::$errMsg .= "Password should be minimum 6 characters long with numbers and alphabets.\n";
                    return false; 
                }
            return true;
        }

        static function isValidName($name){
            if( strlen($name ) < 3 ) { 
                Validation::$errMsg .= "Name should be longer than 2 letters.\n";
                return false; 
            }
            return true;
        }

        static function isValidCapacity($capacity){
            if( $capacity  < 15 ) { 
                Validation::$errMsg .= "Capacity should be minimum 15.\n";
                return false; 
            }
            return true;
        }

        static function isValidAllowedNum($num){
            if($num < 10){ 
                Validation::$errMsg .= "Allowed number should be minimum 10.\n";
                return false; 
            }
            return true;
        }

        static function areValidDates($start,$end){
            $dstart = new DateTime($start);
            $dend = new DateTime($end);
            if($dstart > $dend){ 
                Validation::$errMsg .= "Start date should be before end date.\n";
                return false; 
            }
            return true;
        }

    }
?>