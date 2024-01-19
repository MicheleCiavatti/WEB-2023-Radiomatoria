<?php
    session_start();
    require __DIR__ . "/../classes/dbh.classes.php";
    
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