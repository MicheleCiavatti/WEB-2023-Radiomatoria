<?php

class LoginContr extends Login {
    private $address;
    private $pw;

    public function __construct($address, $pw) {
        $this->address = $address;
        $this->pw = $pw;
    }

     /*------------------ERROR HANDLING---------------*/
    // Empty inputs
    private function emptyInput() {
        return empty($this->address) || empty($this->pw);
    }
    
    public function loginUser() {
        if($this->emptyInput()) {
            if (empty($this->pw)) header('location: ../../login.html?error=emptypw');
            else header('location: ../../login.html?error=emptyaddress');
            exit();
        }
        $this->getUser($this->address, $this->pw);
    }

}