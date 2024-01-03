<?php
    require __DIR__ . "/../classes/dbh.classes.php";
    function getFrequencies($uid) {
        if (!isset($_SESSION['Frequenze'])) {
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
            $result = $s->fetchAll(PDO::FETCH_ASSOC);
            $frequencies = [];
            foreach ($result as $row) {
                $frequencies[] = $row['MHz'];
            }
            $_SESSION['Frequenze'] = $frequencies;
        }
    }