<?php
    function alterProfile($name, $photo, $address, $city, $dob, $mail, $freq, $time_start, $time_end, $clue, $passwd0, $passwd1, $passwd2) {
        $stmt = $this->db->prepare("UPDATE UTENTE SET NomeUtente = ?, FotoProfilo = ?, Indirizzo = ?,
        Citta = ?, DataNascita = FROM_UNIXTIME(?), IndirizzoMail = ?, Indizio = ? WHERE NomeUtente = ?");
        $stmt->bind_param('ssssisss', $name, $photo, $address, $city, $dob, $mail, $clue, $utente['NomeUtente']);
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

    function selectPost($selection) {
        switch($selection) {
            case "create" {
                $stmt = $this->db->prepare("SELECT * FROM CREAZIONE INNER JOIN POST ON CREAZIONE.NrPost = POST.NrPost INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento WHERE CREAZIONE.NomeUtente = ?");                
                $stmt->bind_param('s', $utente['NomeUtente'])
            }
            case "like" {
                $stmt = $this->db->prepare("SELECT * FROM INTERAZIONE INNER JOIN POST ON INTERAZIONE.NrPost = POST.NrPost INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento WHERE INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?");                
                $stmt->bind_param('si', $utente['NomeUtente'], true);
            }
            case "dislike" {
                $stmt = $this->db->prepare("SELECT * FROM INTERAZIONE INNER JOIN POST ON INTERAZIONE.NrPost = POST.NrPost INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento WHERE INTERAZIONE.NomeUtente = ? AND INTERAZIONE.Tipo = ?");                
                $stmt->bind_param('si', $utente['NomeUtente'], false);
            }
            case "comment" {
                $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento
                INNER JOIN SCRITTURA ON COMMENTO.NrCommento = SCRITTURA.NrCommento WHERE SCRITTURA.NomeUtente = ?");                
                $stmt->bind_param('s', $utente['NomeUtente'])
            }
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $templateParams["post"] = $result;
        toDecorate($result);
    }

    function toDecorate($result) {
        $stmt = $this->db->prepare("SELECT ElementId FROM INTERAZIONE WHERE NomeUtente = ? AND Tipo = ?");
        $stmt->bind_param('si', $_SESSION['uid'], true);
        $stmt->execute();
        $element_id_like = $stmt->get_result();

        $stmt->bind_param('si', $_SESSION['uid'], false);
        $stmt->execute();
        $element_id_dislike = $stmt->get_result();
        decorate($element_id_like, $element_id_dislike);
    }

    function notify($text, $receiver, $request) {
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

    function addFollowed($neoseguito) {
        $stmt = $this->db->prepare("INSERT INTO FOLLOW (NomeUtente, NomeSeguito) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $neoseguito);
        $stmt->execute();
        notify("ti ha aggiunto alla sua lista di seguiti", $neoseguito, false);
    }

    function removeFriend($examico) {
        $stmt = $this->db->prepare("DELETE FROM AMICIZIA WHERE NomeUtente = $_SESSION['uid'] AND NomeAmico = ?");
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

    function sortPost($selection) {
        switch($selection) {
            case "data" {
                $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento ORDER BY DataPost DESC");                
            }
            case "like" {
                $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento INNER JOIN INTERAZIONE ON POST.NrPost =
                INTERAZIONE.NrPost WHERE INTERAZIONE.Tipo IS true GROUP BY NrPost ORDER BY COUNT(INTERAZIONE.Tipo) DESC");                
            }
            case "comm" {
                $stmt = $this->db->prepare("SELECT * FROM POST INNER JOIN CONTENUTO ON POST.NrPost = CONTENUTO.NrPost 
                INNER JOIN COMMENTO ON CONTENUTO.NrCommento = COMMENTO.NrCommento GROUP BY NrPost ORDER BY COUNT(CONTENUTO.NrCommento) DESC");                
            }
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $templateParams["post"] = $result;
        toDecorate($result);
    }
    
    function addPost($post_img, $post_text) {
        $stmt = $this->db->prepare("INSERT INTO POST (NrPost, DataPost, TestoPost, ImmaginePost) VALUES (?, NOW(), ?, ?)");
        do {
            $pid = uniqid();
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM CONTENUTO WHERE NrPost = ? OR NrCommento = ?");
            $idcheck->bind_param('s', $pid, $pid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $now = new Date();
        $stmt->bind_param('sss', $pid, $post_img, $post_text);
        $stmt->execute();
        $stmt = $this->db->prepare("INSERT INTO CREAZIONE (NomeUtente, NrPost) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $pid);
        $stmt->execute();
    }
    
    function removePost($pid) {
        $stmt = $this->db->prepare("SELECT NrCommento FROM CONTENUTO WHERE NrPost = ?");
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        foreach($result as $comment) {
            removeComment($comment);
        }
        $stmt = $this->db->prepare("DELETE FROM POST, CREAZIONE, INTERAZIONE WHERE NrPost = ?");
        $stmt->bind_param('s', $pid);
        $stmt->execute();
    }

    function addComment($pid, $cimg, $ctext) {
        $stmt = $this->db->prepare("INSERT INTO COMMENTO
        (NrCommento, DataCommento, ImmagineCommento, TestoCommento) VALUES (?, NOW(), ?, ?)");
        do {
            $cid = uniqid();
            $idcheck = $this->db->prepare("SELECT COUNT(*) FROM CONTENUTO WHERE NrPost = ? OR NrCommento = ?");
            $idcheck->bind_param('s', $pid, $pid);
            $idcheck->execute();
            $result = $idcheck->get_result();
        } while ($result > 0);
        $now = new Date();
        $stmt->bind_param('sss', $cid, $cimg, $ctext);
        $stmt->execute();
        $stmt = $this->db->prepare("INSERT INTO CONTENUTO (NrPost, NrCommento) VALUES (?, ?)");
        $stmt->bind_param('ss', $pid, $cid);
        $stmt->execute();
        $stmt = $this->db->prepare("INSERT INTO SCRITTURA (NomeUtente, NrCommento) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['uid'], $cid);
        $stmt->execute();
    }

    function removeComment($cid) {
        $stmt = $this->db->prepare("DELETE FROM COMMENTO, SCRITTURA, INTERAZIONE, CONTENUTO WHERE NrCommento = ?");
        $stmt->bind_param('s', $cid);
        $stmt->execute();
    }

    function addLikeOrDislike($element_id, $type) {
        $stmt = $this->db->prepare("INSERT INTO INTERAZIONE (NomeUtente, ElementId, Tipo) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $_SESSION['uid'], $element_id, $type);
        $stmt->execute();
    }

    function removeLikeOrDislike($element_id) {
        $stmt = $this->db->prepare("DELETE FROM INTERAZIONE WHERE NomeUtente = ? AND ElementId = ?");
        $stmt->bind_param('ss', $_SESSION['uid'], $element_id);
        $stmt->execute();
    }
?>