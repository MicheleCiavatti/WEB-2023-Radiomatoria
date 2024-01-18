<?php
    function alterProfile($name, $photo, $address, $city, $dob, $mail, $freq, $time_start, $time_end, $clue, $passwd0, $passwd1, $passwd2) {
        $stmt = $this->db->prepare("UPDATE UTENTE SET FotoProfilo = ?, Indirizzo = ?, Citta = ?, DataNascita = FROM_UNIXTIME(?), Indizio = ? WHERE NomeUtente = ?");
        $stmt->bind_param('sssiss', $photo, $address, $city, $dob, $clue, $utente['NomeUtente']);
        $stmt->execute();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM FREQUENZA WHERE MHz = ?");
        $stmt->bind_param('i', $freq);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("INSERT INTO FREQUENZA (MHz) VALUES (?)");
            $stmt->bind_param('i', $freq);
            $stmt->execute();    
        }
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM BANDA WHERE MHz = ? AND NomeUtente = ?");
        $stmt->bind_param('is', $freq, $utente['NomeUtente']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("INSERT INTO BANDA (MHz, NomeUtente) VALUES (?, ?)");
            $stmt->bind_param('is', $freq, $utente['NomeUtente']);
            $stmt->execute();
        }

        $stmt = $this->db->prepare("SELECT OraInizio, OraFine FROM DISPONIBILITA WHERE OraInizio = ? AND OraFine = ? AND NomeUtente = ?");
        $stmt->bind_param('iis', $time_start, $time_end, $utente['NomeUtente']);
        $stmt->execute();
        $result = $stmt->get_result();
        $valid = true;
        $end1 = $time_end;
        if($time_start > $time_end) {
            $end1 += 12;
        }
        foreach($result as $interval) {
            $end2 = $interval["OraFine"]
            if($interval["OraInizio"] > $interval["OraFine"]) {
                $end2 += 12;
            }
            if(($time_start < $interval["OraInizio"] && $interval["OraInizio"] < $end1)
            || ($interval["OraInizio"] < $time_start && $time_start < $end2)) {
                $valid = false;
            }
        }
        if($valid == true) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM FASCIA_ORARIA WHERE OraInizio = ? AND OraFine = ?");
            $stmt->bind_param('ii', $time_start, $time_end);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result == 0) {
                $stmt = $this->db->prepare("INSERT INTO FASCIA_ORARIA (OraInizio, OraFine) VALUES (?, ?)");
                $stmt->bind_param('ii', $time_start, $time_end);
                $stmt->execute();    
            }
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM DISPONIBILITA WHERE OraInizio = ? AND NomeUtente = ?");
            $stmt->bind_param('is', $time_start, $utente['NomeUtente']);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result == 0) {
                $stmt = $this->db->prepare("INSERT INTO DISPONIBILITA (OraInizio, NomeUtente) VALUES (?, ?)");
                $stmt->bind_param('is', $time_start, $utente['NomeUtente']);
                $stmt->execute();
            }    
        }

        $stmt = $this->db->prepare("SELECT Pw FROM UTENTE WHERE NomeUtente = ?");
        $stmt->bind_param('s', $utente['NomeUtente']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == $passwd0 && $passwd1 == $passwd2) {
            $stmt = $this->db->prepare("UPDATE UTENTE SET Pw = ? WHERE NomeUtente = ?");
            $stmt->bind_param('ss', $passwd1, $utente['NomeUtente']);
            $stmt->execute();
        }
    
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM UTENTE WHERE IndirizzoMail = ?");
        $stmt->bind_param('s', $mail);
        $stmt->execute();    
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("UPDATE UTENTE SET IndirizzoMail = ? WHERE NomeUtente = ?");
            $stmt->bind_param('ss', $mail, $utente['NomeUtente']);
            $stmt->execute();
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM UTENTE WHERE NomeUtente = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();    
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("UPDATE UTENTE SET NomeUtente = ? WHERE NomeUtente = ?");
            $stmt->bind_param('ss', $name, $utente['NomeUtente']);
            $stmt->execute();
        }
    }

    function removeFreq($frequenza) {
        $stmt = $this->db->prepare("DELETE FROM BANDA WHERE MHz = ? AND NomeUtente = ?");
        $stmt->bind_param('is', $frequenza, $utente['NomeUtente']);
        $stmt->execute();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM BANDA WHERE MHz = ?");
        $stmt->bind_param('i', $frequenza);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("DELETE FROM FREQUENZA WHERE MHz = ?");
            $stmt->bind_param('i', $frequenza);
            $stmt->execute();
        }
    }

    function removeInterval($orainizio, $orafine) {
        $stmt = $this->db->prepare("DELETE FROM DISPONIBILITA WHERE OraInizio = ? AND OraFine = ? AND NomeUtente = ?");
        $stmt->bind_param('iis', $orainizio, $orafine, $utente['NomeUtente']);
        $stmt->execute();

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM DISPONIBILITA WHERE OraInizio = ? AND OraFine = ?");
        $stmt->bind_param('ii', $orainizio, $orafine);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result == 0) {
            $stmt = $this->db->prepare("DELETE FROM FASCIA_ORARIA WHERE OraInizio = ? AND OraFine = ?");
            $stmt->bind_param('ii', $orainizio, $orafine);
            $stmt->execute();
        }
    }

    function selectPostProfile($relation_selection, $sort_selection, $order) {
        $username = readCookie('NomeUtente');
        $query = "SELECT POST.*, COUNT(CASE WHEN INTERAZIONE.Tipo THEN 1 END) AS LikePost, COUNT(CASE WHEN NOT INTERAZIONE.Tipo THEN 1 END) AS DislikePost,
        CREAZIONE.NomeUtente AS UserPost FROM ((POST INNER JOIN CREAZIONE ON POST.NrPost = CREAZIONE.NrPost) LEFT JOIN INTERAZIONE ON POST.NrPost = INTERAZIONE.ElementId)";
        $bind = 0;

        $decor = "SELECT INTERAZIONE.ElementID FROM INTERAZIONE WHERE INTERAZIONE.ElementId IN
        (SELECT CONTENUTO.NrCommento FROM SCRITTURA INNER JOIN CONTENUTO ON SCRITTURA.NrCommento = CONTENUTO.NrCommento";
    
        switch($relation_selection) {
            case "create" {
                $condition .= " WHERE CREAZIONE.NomeUtente = ?";
                break;
            }
            case "like" {
                case "dislike" {
                    $condition .= " WHERE INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?";
                    $bind += 1;
                    break;
                }
            }
            case "comment" {
                $condition .= " INNER JOIN SCRITTURA ON CONTENUTO.NrCommento = SCRITTURA.NrCommento WHERE SCRITTURA.NomeUtente = ?";
                break;
            }
        }

        $decor .= $condition;
        $decor .= "), (SELECT SCRITTURA.NrCommento FROM SCRITTURA WHERE SCRITTURA.NomeUtente";
        $decor .= $condition;
        $decor .= ")) AND INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?";
                
        $deco = $this->db->prepare($decor);
        if ($relation_selection == "dislike") {
            $deco->bind_param('sisisi', $username, false, $username, false, $username, false);
            $deco->execute();
            $element_id_dislike = $deco->get_result();
            $deco->bind_param('sisisi', $username, false, $username, false, $username, true);
            $deco->execute();
            $element_id_like = $deco->get_result();
        } else if ($relation_selection == "like") {
            $deco->bind_param('sisisi', $username, true, $username, true, $username, false);
            $deco->execute();
            $element_id_dislike = $deco->get_result();
            $deco->bind_param('sisisi', $username, true, $username, true, $username, true);
            $deco->execute();
            $element_id_like = $deco->get_result();
        } else {
            $deco->bind_param('sssi', $username, $username, $username, false);
            $deco->execute();
            $element_id_dislike = $deco->get_result();
            $deco->bind_param('sssi', $username, $username, $username, true);
            $deco->execute();
            $element_id_like = $deco->get_result();
        }

        $query .= $condition;
        $query .= " GROUP BY POST.NrPost";
        switch($sort_selection) {
            case "data" {
                $query .= " ORDER BY DataPost";                
                break;
            }
            case "like" {
                $query .= " HAVING INTERAZIONE.Tipo = ? ORDER BY COUNT(INTERAZIONE.Tipo)";  
                $bind += 1;              
                break;
            }
            case "comm" {
                $query .= " ORDER BY COUNT(CONTENUTO.NrCommento)";                
                break;
            }
        }
        if ($order == true) {
            $query .= " DESC";
        }
        $stmt = $this->db->prepare($query);
        if ($bind == 2) {
            if($relation_selection == "dislike") {
                $stmt->bind_param('sii', $utente['NomeUtente'], false, true);
            } else {
                $stmt->bind_param('sii', $utente['NomeUtente'], true, true);
            }
        } else if($bind == 1) {
            if($relation_selection == "dislike") {
                $stmt->bind_param('si', $utente['NomeUtente'], false);
            } else {
                $stmt->bind_param('si', $utente['NomeUtente'], true);
            }
        } else {
            $stmt->bind_param('s', $utente['NomeUtente']);
        }
        $stmt->execute();
        $post_list = $stmt->get_result();
    }

    function notify($text, $receiver, $request) {
        $blockcheck = $this->db->prepare("SELECT COUNT(*) FROM BLOCCO WHERE NomeUtente = ? AND NomeBloccato = ?");
        $blockcheck->bind_param('ss', $receiver, $_SESSION['uid']);
        $blockcheck->execute();
        $result = $blockcheck->get_result();
        if($request == true) {
            $repeatcheck = $this->db->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE MittenteNotifica = ? AND Richiesta = ?");
            $repeatcheck->bind_param('si', $_SESSION['uid'], true);
            $repeatcheck->execute();
            $result += $repeatcheck->get_result();
        }
        if($result == 0) {
            $stmt = $this->db->prepare("INSERT INTO NOTIFICA (IdNotifica, TestoNotifica, MittenteNotifica, Richiesta) VALUES (?, ?, ?, ?)");
            do {
                $nid = uniqid();
                $idcheck = $this->db->prepare("SELECT COUNT(*) FROM NOTIFICA WHERE IdNotifica = ?");
                $idcheck->bind_param('s', $nid);
                $idcheck->execute();
                $result = $idcheck->get_result();
            } while ($result > 0);
            $stmt->bind_param('sssi', $nid, $text, $_SESSION['uid'], false);
            $stmt->execute();
    
            $stmt = $this->db->prepare("INSERT INTO CAUSA (IdNotifica, NomeUtente) VALUES (?, ?)");
            $stmt->bind_param('ss', $nid, $_SESSION['uid']);
            $stmt->execute();
    
            $stmt = $this->db->prepare("INSERT INTO RICEZIONE (IdNotifica, NomeUtente) VALUES (?, ?)");
            $stmt->bind_param('ss', $nid, $receiver);
            $stmt->execute();
        }
    }

    function addFollowed($neoseguito) {
        $stmt = $this->db->prepare("INSERT INTO FOLLOW (NomeUtente, NomeSeguito) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $neoseguito);
        $stmt->execute();
        notify("ti ha aggiunto alla sua lista di seguiti", $neoseguito, false);
    }

    function addBlocked($bloccato) {
        $stmt = $this->db->prepare("INSERT INTO BLOCCO (NomeUtente, NomeBloccato) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $bloccato);
        $stmt->execute();
        notify("ti ha bloccato", $bloccato, false);
    }

    function removeFriend($examico) {
        $stmt = $this->db->prepare("DELETE FROM AMICIZIA WHERE NomeUtente = ? AND NomeAmico = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $examico);
        $stmt->execute();
        $stmt->bind_param('ss', $examico, $_SESSION['uid']);
        $stmt->execute();
        notify("ti ha rimosso dalla sua lista di amici", $examico, false);
    }

    function removeFollowed($exseguito) {
        $stmt = $this->db->prepare("DELETE FROM FOLLOW WHERE NomeUtente = $_SESSION['uid'] AND NomeSeguito = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $exseguito);
        $stmt->execute();
        notify("ti ha rimosso dalla sua lista di seguiti", $exseguito, false);
    }

    function removeBlocked($perdonato) {
        $stmt = $this->db->prepare("DELETE FROM BLOCCO WHERE NomeUtente = $_SESSION['uid'] AND NomeBloccato = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $perdonato);
        $stmt->execute();
        notify("ha rimosso il tuo blocco", $perdonato, false);
    }

    function isFriend($nome) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM AMICIZIA WHERE NomeUtente = ? AND NomeAmico = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    function isFollowed($nome) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM FOLLOW WHERE NomeUtente = ? AND NomeSeguito = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    function isBlocked($nome) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM BLOCCO WHERE NomeUtente = ? AND NomeBloccato = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
?>