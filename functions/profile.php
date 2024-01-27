<?php
    session_start();
    require __DIR__ . "/classes/dbh.classes.php";

    function profileAccess($username) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT UTENTI.* FROM UTENTI WHERE UTENTI.NomeUtente = ?");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $dbh->connect()->prepare("SELECT BANDE.MHz FROM BANDE WHERE BANDE.NomeUtente = ? ORDER BY BANDE.MHz");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $frequenze = [];
        foreach ($result as $row) {
            $frequenze[] = $row[0];
        }

        $stmt = $dbh->connect()->prepare("SELECT DISPONIBILITA.OraInizio, DISPONIBILITA.OraFine FROM DISPONIBILITA WHERE DISPONIBILITA.Utente = ? ORDER BY DISPONIBILITA.OraInizio");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $orari = [];
        foreach ($result as $row) {
            $orari[] = array($row[0], $row[1]);
        }

        $stmt = $dbh->connect()->prepare("SELECT AMICIZIA.Amico2, UTENTI.FotoProfilo FROM AMICIZIA INNER JOIN UTENTI ON AMICIZIA.Amico2 = UTENTI.NomeUtente WHERE AMICIZIA.Amico1 = ?");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $amici = [];
        foreach ($result as $row) {
            $amici[] = array($row[0], $row[1]);
        }

        $stmt = $dbh->connect()->prepare("SELECT FOLLOW.Followed, UTENTI.FotoProfilo FROM FOLLOW INNER JOIN UTENTI ON FOLLOW.Followed = UTENTI.NomeUtente WHERE FOLLOW.Follower = ?");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $seguiti = [];
        foreach ($result as $row) {
            $seguiti[] = array($row[0], $row[1]);
        }

        $stmt = $dbh->connect()->prepare("SELECT BLOCCO.Bloccato, UTENTI.FotoProfilo FROM BLOCCO INNER JOIN UTENTI ON BLOCCO.Bloccato = UTENTI.NomeUtente WHERE BLOCCO.Bloccante = ?");
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $bloccati = [];
        foreach ($result as $row) {
            $bloccati[] = array($row[0], $row[1]);
        }

        return array($utente, $frequenze, $orari, $amici, $seguiti, $bloccati);
    }

    function alterProfile($name, $photo, $address, $city, $dob, $mail, $freq, $time_start, $time_end, $clue, $passwd0, $passwd1, $passwd2) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET FotoProfilo = ?, Indirizzo = ?, Città = ?, DataNascita = FROM_UNIXTIME(?), Indizio = ? WHERE NomeUtente = ?");
        if(!$stmt->execute(array($photo, $address, $city, $dob, $clue, $_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        setcookie("FotoProfilo", $photo, 0, "/");
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
        if(!$stmt->execute(array($freq, $_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == 0) {
            $stmt = $dbh->connect()->prepare("INSERT INTO BANDE (MHz, NomeUtente) VALUES (?, ?)");
            if(!$stmt->execute(array($freq, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }

        $stmt = $dbh->connect()->prepare("SELECT OraInizio, OraFine FROM DISPONIBILITA WHERE Utente = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetchAll(PDO::FETCH_NUM);
        $valid = true;
        $end1 = $time_end;
        if($time_start > $time_end) {
            $end1 += 12;
        }
        foreach($result as $interval) {
            $end2 = $interval[1]
            if($interval[0] > $interval[1]) {
                $end2 += 12;
            }
            if(($time_start < $interval[0] && $interval[0] < $end1)
            || ($interval[0] < $time_start && $time_start < $end2)) {
                $valid = false;
                break;
            }
        }
        if($valid == true) {
            $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM DISPONIBILITA WHERE OraInizio = ? AND NomeUtente = ?");
            if(!$stmt->execute(array($time_start, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if($result[0] == 0) {
                $stmt = $dbh->connect()->prepare("INSERT INTO DISPONIBILITA (OraInizio, OraFine, Utente) VALUES (?, ?, ?)");
                if(!$stmt->execute(array($time_start, $time_end, $_COOKIE['NomeUtente']))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            }    
        }

        $stmt = $dbh->connect()->prepare("SELECT Pw FROM UTENTI WHERE NomeUtente = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == $passwd0 && $passwd1 == $passwd2) {
            $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET Pw = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($passwd1, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }
    
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM UTENTI WHERE IndirizzoMail = ?");
        if(!$stmt->execute(array($mail))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == 0) {
            $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET IndirizzoMail = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($mail, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }

        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM UTENTI WHERE NomeUtente = ?");
        if(!$stmt->execute(array($name))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == 0) {
            $stmt = $dbh->connect()->prepare("UPDATE BANDE SET NomeUtente = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE DISPONIBILITA SET Utente = ? WHERE Utente = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE POST SET Creatore = ? WHERE Creatore = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE COMMENTI SET Creatore = ? WHERE Creatore = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE INTERAZIONI SET Creatore = ? WHERE Creatore = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE NOTIFICHE SET Ricevente = ? WHERE Ricevente = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE NOTIFICHE SET Mandante = ? WHERE Mandante = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE AMICIZIA SET Amico1 = ? WHERE Amico1 = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE FOLLOW SET Follower = ? WHERE Follower = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE BLOCCO SET Bloccante = ? WHERE Bloccante = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE AMICIZIA SET Amico2 = ? WHERE Amico2 = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE FOLLOW SET Followed = ? WHERE Followed = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE BLOCCO SET Bloccato = ? WHERE Bloccato = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET NomeUtente = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($name, $_COOKIE['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            setcookie("NomeUtente", $name, 0, "/");
        }
    }

    function selectPostProfile($username, $relation_selection, $sort_selection, $order) {
        $dbh = new Dbh;
        $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikePost
        FROM (POST LEFT JOIN INTERAZIONI ON POST.NrPost = INTERAZIONI.ElementId) LEFT JOIN COMMENTI ON POST.NrPost = COMMENTI.NrPost";

        switch($relation_selection) {
            case "create":
                $condition = " WHERE POST.Creatore = ?";
                break;
            case "like":
                case "dislike":
                    $condition = " WHERE INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
                    break;
            case "comment":
                $condition = " WHERE COMMENTI.Creatore = ?";
                break;
            case "none":
                default:
                    $condition = "";
                    break;
        }

        $query .= $condition;
        $query .= " GROUP BY POST.NrPost";
        switch($sort_selection) {
            case "data":
                $query .= " ORDER BY DataPost";                
                break;
            case "like":
                $query .= " HAVING INTERAZIONI.Tipo = ? ORDER BY COUNT(INTERAZIONI.Tipo)";  
                break;
            case "comm":
                $query .= " ORDER BY COUNT(COMMENTI.NrCommento)";                
                break;
            case "none":
                default:
                    $order = false;
                    break;
        }
        if ($order == true) {
            $query .= " DESC";
        }
        $stmt = $dbh->connect()->prepare($query);

        if(isset($_COOKIE['NomeUtente'])) {
            $decor = "SELECT INTERAZIONI.ElementId FROM (INTERAZIONI LEFT JOIN POST ON INTERAZIONI.ElementId = POST.NrPost) LEFT JOIN COMMENTI ON INTERAZIONI.ElementId = COMMENTI.NrCommento";
            $decor .= $condition;
            $decor .= " AND INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
            $deco = $dbh->connect()->prepare($decor);
        }
                
        switch($relation_selection) {
            case "like": 
                if(isset($_COOKIE['NomeUtente'])) {
                    if(!$deco->execute(array($utente['NomeUtente'], true, $_COOKIE['NomeUtente'], false))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                    if(!$deco->execute(array($utente['NomeUtente'], true, $_COOKIE['NomeUtente'], true))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                }
                if($sort_selection == "like") {
                    if(!$stmt->execute(array($utente['NomeUtente'], true, true))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                } else {
                    if(!$stmt->execute(array($utente['NomeUtente'], true))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                }
                break;
            case "dislike":
                if(isset($_COOKIE['NomeUtente'])) {
                    if(!$deco->execute(array($utente['NomeUtente'], false, $_COOKIE['NomeUtente'], false))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                    if(!$deco->execute(array($utente['NomeUtente'], false, $_COOKIE['NomeUtente'], true))) {
                        $deco = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                    $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                }
                if($sort_selection == "like") {
                    if(!$stmt->execute(array($utente['NomeUtente'], false, true))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                } else {
                    if(!$stmt->execute(array($utente['NomeUtente'], false))) {
                        $stmt = null;
                        header('location: ../../login.html?error=stmtfailed');
                        exit();
                    }
                }
                break;
            case "create":
                case "comment":
                    if(isset($_COOKIE['NomeUtente'])) {
                        if(!$deco->execute(array($utente['NomeUtente'], $_COOKIE['NomeUtente'], false))) {
                            $deco = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                        $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                        if(!$deco->execute(array($utente['NomeUtente'], $_COOKIE['NomeUtente'], true))) {
                            $deco = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                        $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                    }
                    if($sort_selection == "like") {
                        if(!$stmt->execute(array($utente['NomeUtente'], true))) {
                            $stmt = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                    } else {
                        if(!$stmt->execute(array($utente['NomeUtente']))) {
                            $stmt = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                    }
                    break;
            case "none":
                default:
                    if(isset($_COOKIE['NomeUtente'])) {
                        if(!$deco->execute(array($_COOKIE['NomeUtente'], false))) {
                            $deco = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                        $element_id_dislike = $deco->fetchAll(PDO::FETCH_NUM);
                        if(!$deco->execute(array($_COOKIE['NomeUtente'], true))) {
                            $deco = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                        $element_id_like = $deco->fetchAll(PDO::FETCH_NUM);
                    }
                    if($sort_selection == "like") {
                        if(!$stmt->execute(array(true))) {
                            $stmt = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                    } else {
                        if(!$stmt->execute()) {
                            $stmt = null;
                            header('location: ../../login.html?error=stmtfailed');
                            exit();
                        }
                    }
                    break;
        }
        $post_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array($post_list, $element_id_like, $element_id_dislike);
    }

    function notify($text, $receiver, $request) {
        require_once "../classes/dbh.classes.php";
        $dbh = new Dbh;
        $blockcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
        if ($blockcheck === false) {
            error_log("Errore nella preparazione della query SELECT per il controllo del blocco.");
            exit;
        }
        if(!$blockcheck->execute(array($receiver, $_COOKIE['NomeUtente']))) {
            error_log("Errore nell'esecuzione della query SELECT per il controllo del blocco: " . print_r($blockcheck->errorInfo(), true));
            exit;
        }
        $block = $blockcheck->fetch(PDO::FETCH_NUM);
        $check = $block[0];
        if($request == true) {
            $repeatcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE Mandante = ? AND Richiesta = ?");
            if ($repeatcheck === false) {
                error_log("Errore nella preparazione della query SELECT per il controllo del duplicato.");
                exit;
            }
            if(!$repeatcheck->execute(array($_COOKIE['NomeUtente'], true))) {
                error_log("Errore nell'esecuzione della query SELECT per il controllo del duplicato: " . print_r($repeatcheck->errorInfo(), true));
                exit;
            }
            $repeat = $repeatcheck->fetch(PDO::FETCH_NUM);
            $check += $repeat[0];
        }
        if($check == 0) {
            do {
                $id = null;
                $nid = uniqid();
                $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE IdNotifica = ?");
                if ($idcheck === false) {
                    error_log("Errore nella preparazione della query SELECT per la creazione dell'id.");
                    exit;
                }
                if(!$idcheck->execute(array($nid))) {
                    error_log("Errore nell'esecuzione della query SELECT per la creazione dell'id: " . print_r($idcheck->errorInfo(), true));
                    exit();
                }
                $id = $idcheck->fetch(PDO::FETCH_NUM);
            } while ($id[0] > 0);
            $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICA (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                error_log("Errore nella preparazione della query INSERT.");
                exit;
            }
            if(!$stmt->execute(array($receiver, $_COOKIE['NomeUtente'], $nid, $text, $request, false))) {
                error_log("Errore nell'esecuzione della query INSERT: " . print_r($stmt->errorInfo(), true));
            }
        }
    }
    
    function isFriend($nome) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM AMICIZIA WHERE Amico1 = ? AND Amico2 = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $nome))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }

    function isFollowed($nome) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM FOLLOW WHERE Follower = ? AND Followed = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $nome))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }

    function isBlocked($nome) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $nome))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result[0];
    }
?>