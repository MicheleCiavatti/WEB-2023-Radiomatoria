<?php

class LoginContr extends Login {
    private $uid;
    private $pw;

    public function __construct($uid, $pw) {
        $this->uid = $uid;
        $this->pw = $pw;
    }

     /*------------------ERROR HANDLING---------------*/
    // Empty inputs
    private function emptyInput() {
        return empty($this->uid) || empty($this->pw);
    }
    
    public function loginUser() {
        if($this->emptyInput()) {
            if (empty($this->pw)) header('location: ../login.php?error=emptypw');
            else header('location: ../login.php?error=emptyuid');
            exit();
        }
        $this->getUser($this->uid, $this->pw);
    }

}