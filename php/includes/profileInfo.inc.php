<?php
    session_start();
    require_once __DIR__ . "/../classes/dbh.classes.php";
    
    function getFrequencies($uid) {
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT MHz
                FROM BANDE
                WHERE NomeUtente = ?;'
        );
        if(!$s->execute(array($uid))) {
            $s = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $s->fetchAll(PDO::FETCH_NUM);
        //var_dump($result);
        $frequencies = [];
        foreach ($result as $row) {
            $frequencies[] = $row[0];
        }
        $_SESSION['Frequenze'] = $frequencies;
    }

    function getTimeSlots($uid) {
        $dbh = new Dbh;
        $s = $dbh->connect()->prepare(
            'SELECT OraInizio, OraFine
             FROM DISPONIBILITA
             WHERE Utente = ?;'
        );
        if(!$s->execute(array($uid))) {
            $s = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $s->fetchAll(PDO::FETCH_NUM);
        $time = [];
        foreach ($result as $row) {
            $time[] = array($row[0], $row[1]);
        }
        //var_dump($time);
        $_SESSION['Orari'] = $time;
    }