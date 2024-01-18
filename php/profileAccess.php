<?php

    public function profileAccess($username) {
        $stmt->db->prepare("SELECT UTENTE.* FROM UTENTE WHERE UTENTE.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $utente = $stmt->get_result();

        $stmt->db->prepare("SELECT BANDE.MHz FROM BANDE WHERE BANDE.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $frequenze = $stmt->get_result();

        $stmt->db->prepare("SELECT DISPONIBILITA.OraInizio, DISPONIBILITA.OraFine FROM DISPONIBILITA WHERE DISPONIBILITA.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $orari = $stmt->get_result();

        $stmt->db->prepare("SELECT AMICIZIA.NomeAmico FROM AMICIZIA WHERE AMICIZIA.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $amici = $stmt->get_result();

        $stmt->db->prepare("SELECT FOLLOW.NomeSeguito FROM FOLLOW WHERE FOLLOW.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $seguiti = $stmt->get_result();

        $stmt->db->prepare("SELECT BLOCCO.NomeBloccato FROM BLOCCO WHERE BLOCCO.NomeUtente = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $bloccati = $stmt->get_result();
    }

?>
