<?php
require_once '../classes/dbh.classes.php';
session_start();

if (isset($_POST['new_name']) && isset($_POST['new_address']) && isset($_POST['new_city']) && isset($_POST['new_dob']) && isset($_POST['new_mail'])) {
    $uid = $_SESSION['NomeUtente'];
    $name = $_POST['new_name'];
    $address = $_POST['new_address'];
    $city = $_POST['new_city'];
    $dob = $_POST['new_dob'];
    $mail = $_POST['new_mail'];

    $dbh = new Dbh;

    $date = date_format(date_create_from_format('Y-m-d', $dob), 'Y-m-d');
    if($date > date('Y-m-d') || $date < '1900-01-01') {
        header('location: ../profile.php?id=' . $uid . '&error=invaliddate');
        exit();
    }
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header('location: ../profile.php?id=' . $uid . '&error=invalidmail');
        exit();
    }

    $s = $dbh->connect()->prepare('SELECT * FROM UTENTI WHERE (NomeUtente = ? OR IndirizzoMail = ?) AND NOT (NomeUtente = ?);');
    if (!$s->execute(array($name, $mail, $uid))) {
        $s = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    if($s->rowCount() > 0) {
        header('location: ../profile.php?id=' . $uid . '&error=duplicatenameormail');
        exit();
    }

    if($uid != $name) {
        $s = $dbh->connect()->prepare('UPDATE AMICIZIA SET Amico1 = ? WHERE Amico1 = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE AMICIZIA SET Amico2 = ? WHERE Amico2 = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE FOLLOW SET Followed = ? WHERE Followed = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE FOLLOW SET Follower = ? WHERE Follower = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE BLOCCO SET Bloccante = ? WHERE Bloccante = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE BLOCCO SET Bloccato = ? WHERE Bloccato = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE BANDE SET NomeUtente  = ? WHERE NomeUtente  = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE DISPONIBILITA  SET Utente  = ? WHERE Utente  = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE POST SET Creatore = ? WHERE Creatore = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE COMMENTI SET Creatore = ? WHERE Creatore = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE COMMENTI SET AutoreCommento = ? WHERE AutoreCommento = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE NOTIFICHE SET Ricevente = ? WHERE Ricevente = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE NOTIFICHE SET Mandante = ? WHERE Mandante = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE INTERAZIONI SET Creatore = ? WHERE Creatore = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE INTERAZIONI SET Interagente  = ? WHERE Interagente  = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE REAZIONI SET Creatore = ? WHERE Creatore = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $s = $dbh->connect()->prepare('UPDATE REAZIONI SET Reagente = ? WHERE Reagente = ?;');
        if (!$s->execute(array($name, $uid))) {
            $s = null;
            header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
            exit();
        }
        $_SESSION['NomeUtente'] = $name;
    }

    $s = $dbh->connect()->prepare(
        'UPDATE UTENTI
            SET NomeUtente = ?, Indirizzo = ?, Città = ?, DataNascita = ?, IndirizzoMail = ?
            WHERE NomeUtente = ?;'
    );
    if (!$s->execute(array($name, $address, $city, $dob, $mail, $uid))) {
        $s = null;
        header('location: ../profile.php?id=' . $uid . '&error=stmtfailed');
        exit();
    }
    header('location: ../profile.php?id=' . $_SESSION['NomeUtente'] . '&error=none');
} else {
    error_log("Variabile non settata");
}