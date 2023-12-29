<?php

class SignupContr extends Signup {
    private $uid;
    private $address;
    private $city;
    private $mail;
    private $birthdate;
    private $pw;
    private $pwrepeat;
    private $clue;

    public function __construct($uid, $address, $city, $mail, $birthdate, $pw, $pwrepeat, $clue) {
        $this->uid = $uid;
        $this->address = $address;
        $this->city = $city;
        $this->mail = $mail;
        $this->birthdate = $birthdate;
        $this->pw = $pw;
        $this->pwrepeat = $pwrepeat;
        $this->clue = $clue;
    }

     /*------------------ERROR HANDLING---------------*/
     // Empty inputs
    private function emptyInput() {
        return empty($this->uid) || empty($this->address) 
            || empty($this->city) || empty($this->mail)
            || empty($this->birthdate) || empty($this->pw)
            || empty($this->pwrepeat) || empty($this->clue);
    }

    // Valid username
    private function invalidUid() {
        return !preg_match("/^[a-zA-Z0-9]*$/", $this->uid);
    }

    // Valid email
    private function invalidMail() {
        return !filter_var($this->mail, FILTER_VALIDATE_EMAIL);
    }

    // pw and pwrepeat not matching
    private function pwNotMatch() {
        return $this->pw !== $this->pwrepeat;
    }

    // user already registered
    private function userRegistered() {
        return $this->isUserRegistered($this->uid, $this->mail);
    }
    
    public function signupUser() {
        if($this->emptyInput()) {
            header('location: ../login.php?error=emptyinput');
            exit();
        }
        if($this->invalidUid()) {
            header('location: ../login.php?error=username');
            exit();
        }
        if($this->invalidMail()) {
            header('location: ../login.php?error=email');
            exit();
        }
        if($this->pwNotMatch()) {
            header('location: ../login.php?error=passwordnotmatch');
            exit();
        }
        if($this->userRegistered()) {
            header('location: ../login.php?error=userormailtaken');
            exit();
        }
        $this->setUser($this->uid, $this->address, $this->city, $this->mail, $this->birthdate, $this->pw, $this->clue);
    }

}