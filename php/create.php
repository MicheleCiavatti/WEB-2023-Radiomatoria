<?php
    function create_new_account($address, $passwd1, $passwd2, $clue, $usrname) {
        if($passwd1 == $passwd2) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM UTENTE WHERE IndirizzoMail = ?");
            $stmt->bind_param('s', $address);
            $stmt->execute();    
            $result = $stmt->get_result();
            if($result == 0) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM UTENTE WHERE NomeUtente = ?");
                $stmt->bind_param('s', $usrname);
                $stmt->execute();    
                $result = $stmt->get_result();
                if($result == 0) {
                    $stmt = $this->db->prepare("INSERT INTO UTENTE
                    (NomeUtente, FotoProfilo, Indirizzo, Citta, DataNascita, IndirizzoMail, Indizio, Pw) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssssisss', $usrname, NULL, NULL, NULL, NULL, $address, $clue, $passwd1);
                    $stmt->execute();
                    $result = NULL;    
                } else {
                    $result = "Nome utente inserito presente nel sito.";
                }
            } else {
                $result = "Indirizzo e-mail inserito presente nel sito.";
            }
        } else {
            $result = "La password non coincide con la relativa conferma.";
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
?>