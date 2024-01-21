<?php
    function alterProfile($name, $photo, $address, $city, $dob, $mail, $freq, $time_start, $time_end, $clue, $passwd0, $passwd1, $passwd2) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET FotoProfilo = ?, Indirizzo = ?, Città = ?, DataNascita = FROM_UNIXTIME(?), Indizio = ? WHERE NomeUtente = ?");
        if(!$stmt->execute(array($photo, $address, $city, $dob, $clue, $utente['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        
        $stmt = $dbh->connect()->prepare("SELECT COUNT(*) FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
        if(!$stmt->execute(array($freq, $utente['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == 0) {
            $stmt = $dbh->connect()->prepare("INSERT INTO BANDE (MHz, NomeUtente) VALUES (?, ?)");
            if(!$stmt->execute(array($freq, $utente['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }

        $stmt = $dbh->connect()->prepare("SELECT OraInizio, OraFine FROM DISPONIBILITA WHERE Utente = ?");
        if(!$stmt->execute(array($utente['NomeUtente']))) {
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
            if(!$stmt->execute(array($time_start, $utente['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if($result[0] == 0) {
                $stmt = $dbh->connect()->prepare("INSERT INTO DISPONIBILITA (OraInizio, OraFine, Utente) VALUES (?, ?, ?)");
                if(!$stmt->execute(array($time_start, $time_end, $utente['NomeUtente']))) {
                    $stmt = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
            }    
        }

        $stmt = $dbh->connect()->prepare("SELECT Pw FROM UTENTI WHERE NomeUtente = ?");
        if(!$stmt->execute(array($utente['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $result = $stmt->fetch(PDO::FETCH_NUM);
        if($result[0] == $passwd0 && $passwd1 == $passwd2) {
            $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET Pw = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($passwd1, $utente['NomeUtente']))) {
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
            if(!$stmt->execute(array($mail, $utente['NomeUtente']))) {
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
            $stmt = $dbh->connect()->prepare("UPDATE UTENTI SET NomeUtente = ? WHERE NomeUtente = ?");
            if(!$stmt->execute(array($name, $utente['NomeUtente']))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }
    }

    function removeFreq($frequenza) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
        if(!$stmt->execute(array($frequenza, $utente['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function removeInterval($orainizio, $orafine) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM DISPONIBILITA WHERE OraInizio = ? AND OraFine = ? AND NomeUtente = ?");
        if(!$stmt->execute(array($orainizio, $orafine, $utente['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
    }

    function selectPostProfile($relation_selection, $sort_selection, $order) {
        $dbh = new Dbh;
        $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONI.Tipo THEN 1 END) AS DislikePost
        FROM (POST LEFT JOIN INTERAZIONI ON POST.NrPost = INTERAZIONI.ElementId) LEFT JOIN COMMENTI ON POST.NrPost = COMMENTI.NrPost";

        $decor = "SELECT INTERAZIONI.ElementId FROM (INTERAZIONI LEFT JOIN POST ON INTERAZIONI.ElementId = POST.NrPost) LEFT JOIN COMMENTI ON INTERAZIONI.ElementId = COMMENTI.NrCommento";
    
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

        $decor .= $condition;
        $decor .= " AND INTERAZIONI.Creatore = ? AND INTERAZIONI.Tipo = ?";
                
        $deco = $dbh->connect()->prepare($decor);
        switch($relation_selection) {
            case "like": 
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
        $dbh = new Dbh;
        $blockcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
        if(!$blockcheck->execute(array($receiver, $_COOKIE['NomeUtente']))) {
            $blockcheck = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        $block = $blockcheck->fetch(PDO::FETCH_NUM);
        if($request == true) {
            $repeatcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE Mandante = ? AND Richiesta = ?");
            if(!$repeatcheck->execute(array($_COOKIE['NomeUtente'], true))) {
                $repeatcheck = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
            $repeat = $repeatcheck->fetch(PDO::FETCH_NUM);
        }
        if($block[0] == 0 && $repeat[0] == 0) {
            do {
                $id = null;
                $nid = uniqid();
                $idcheck = $dbh->connect()->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE IdNotifica = ?");
                if(!$idcheck->execute(array($nid))) {
                    $idcheck = null;
                    header('location: ../../login.html?error=stmtfailed');
                    exit();
                }
                $id = $idcheck->fetch(PDO::FETCH_NUM);
            } while ($id[0] > 0);
            $stmt = $dbh->connect()->prepare("INSERT INTO NOTIFICA (Ricevente, Mandante, IdNotifica, TestoNotifica, Richiesta, Lettura) VALUES (?, ?, ?, ?, ?, ?)");
            if(!$stmt->execute(array($receiver, $_COOKIE['NomeUtente'], $nid, $text, false, false))) {
                $stmt = null;
                header('location: ../../login.html?error=stmtfailed');
                exit();
            }
        }
    }

    function addFollowed($neoseguito) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("INSERT INTO FOLLOW (Follower, Followed) VALUES (?, ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $neoseguito))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        notify("ti ha aggiunto alla sua lista di seguiti", $neoseguito, false);
    }

    function addBlocked($bloccato) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("INSERT INTO BLOCCO (Bloccante, Bloccato) VALUES (?, ?)");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $bloccato))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        notify("ti ha bloccato", $bloccato, false);
    }

    function removeFriend($examico) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM AMICIZIA WHERE Amico1 = ? AND Amico2 = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $examico))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        if(!$stmt->execute(array($examico, $_COOKIE['NomeUtente']))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        notify("ti ha rimosso dalla sua lista di amici", $examico, false);
    }

    function removeFollowed($exseguito) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM FOLLOW WHERE Follower = ? AND Followed = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $exseguito))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        notify("ti ha rimosso dalla sua lista di seguiti", $exseguito, false);
    }

    function removeBlocked($perdonato) {
        $dbh = new Dbh;
        $stmt = $dbh->connect()->prepare("DELETE FROM BLOCCO WHERE Bloccante = ? AND Bloccato = ?");
        if(!$stmt->execute(array($_COOKIE['NomeUtente'], $perdonato))) {
            $stmt = null;
            header('location: ../../login.html?error=stmtfailed');
            exit();
        }
        notify("ha rimosso il tuo blocco", $perdonato, false);
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