<?php
    function getClue($address){
        $stmt = $this->db->prepare("SELECT Indizio FROM UTENTI WHERE IndirizzoMail = ?");
        $stmt->bind_param('s', $address);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result === NULL){
            $result = "Indirizzo e-mail digitato non presente in questo sito.";
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function process_login($email, $passwd) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM UTENTE WHERE IndirizzoMail = ?");
        $stmt->bind_param('s', $address);
        $stmt->execute();    
        $result = $stmt->get_result();
        if($result == 0) {
            $login = new LoginContr($email, $passwd);
            $login->loginUser();
        } else {
            $result = "Indirizzo e-mail digitato non presente in questo sito.";
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>