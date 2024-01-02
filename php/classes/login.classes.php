<?php

class Login extends Dbh {

    protected function getUser($uid, $pw) {
        $s = $this->connect()->prepare(
            'SELECT *
             FROM UTENTI
             WHERE NomeUtente = ?;'
        );
        if (!$s->execute(array($uid))) {
            $s = null;
            header('location: ../../login.html?errorstmtfailed');
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
            $_SESSION['uid'] = $result[0]['NomeUtente'];
            $_SESSION['img'] = $result[0]['FotoProfilo'];
            $s = null;
        }
    }
}