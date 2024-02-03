<?php

class Login extends Dbh {

    protected function getUser($address, $pw) {
        $s = $this->connect()->prepare(
            'SELECT *
             FROM UTENTI
             WHERE IndirizzoMail = ?;'
        );
        if (!$s->execute(array($address))) {
            $s = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        if($s->rowCount() == 0) {
            $s = null;
            header('location: ../../login.html?error=usernotfound');
            exit();
        }
        $result = $s->fetchAll(PDO::FETCH_ASSOC);
        if (!password_verify($pw, $result[0]['Password'])) {
            $s = null;
            header('location: ../../login.html?error=wrongpassword');
            exit();
        } else {
            session_start();
            $this->access(
                $result[0]['NomeUtente'], 
                $result[0]['FotoProfilo'], 
                $result[0]['Indirizzo'], 
                $result[0]['Città'], 
                $result[0]['DataNascita'], 
                $result[0]['IndirizzoMail'], 
                $result[0]['Indizio']);
            $s = null;
        }
    }
}