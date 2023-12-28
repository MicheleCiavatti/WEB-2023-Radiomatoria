<?php

class DatabaseHelper {
    private $db;
    public function __construct($serverName, $userName, $pw, $dbName) {
        $this->db = new mysqli($serverName, $userName, $pw, $dbName);
        if (!$this->db->connect_error) {
            die("". $this->db->connect_error);
        }
    }

    public function checkLogin($mail, $pw) {
        $s = $this->db->prepare(
            "SELECT *
             FROM UTENTI
             WHERE IndirizzoMail = ? AND Password = ?"
            );
        $s->bind_param("s", $mail);
        $s->bind_param("s", $pw);
        $s->execute();
        $result = $s->get_result();
        return $result->num_rows > 0;
    }
}
?>