<?php

class DatabaseHelper {
    private $db;
    public function __construct($serverName, $userName, $pw, $dbName) {
        $this->db = new mysqli($serverName, $userName, $pw, $dbName);
        if (!$this->db->connect_error) {
            die("". $this->db->connect_error);
        }
    }

    public function checkLogin($userName, $pw) {
        $s = $this->db->prepare(
            "SELECT *
             FROM UTENTI
             WHERE NomeUtente = ? AND Password = ?"
            );
        $s->bind_param("s", $userName);
        $s->bind_param("s", $pw);
        $s->execute();
        $result = $s->get_result();
        return $result->num_rows > 0;
    }
}
?>