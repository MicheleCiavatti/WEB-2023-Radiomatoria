<?php

class Signup extends Dbh {

    protected function isUserRegistered($uid, $mail) {
        $s = $this->connect()->prepare(
            'SELECT NomeUtente
             FROM UTENTI
             WHERE NomeUtente = ? OR IndirizzoMail = ?;'
        );
        if(!$s->execute(array($uid, $mail))) {
            $s = null;
            header('location: ../../signup.html?error=stmtfailed');
            exit();
        }
        // If rowCount() > 0 then there is already a user with that name or mail
        return $s->rowCount() > 0;
    }

    protected function setUser($uid, $address, $city, $mail, $birthdate, $pw, $clue) {
        $s = $this->connect()->prepare(
            'INSERT INTO UTENTI (NomeUtente, FotoProfilo, Indirizzo, Città, Password, DataNascita, IndirizzoMail, Indizio)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?);'
        );
        // For security, we save an hashed password in the database
        $hashedPw = password_hash($pw, PASSWORD_DEFAULT);
        if(!$s->execute(array($uid,'./img/default.png', $address, $city, $hashedPw, $birthdate, $mail, $clue))) {
            $s = null;
            header('location: ../../signup.html?errorstmtfailed');
            exit();
        }
        session_start();
        $this->access($uid, './img/default.png', $address, $city, $birthdate, $mail, $clue);
        $s = null;
    }
}