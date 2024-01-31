<?php
require_once __DIR__ . "/../classes/dbh.classes.php";

function getPosts($username) {
    $dbh = new Dbh;
    if ($username == null) {
        $s = $dbh->connect()->prepare(
            'SELECT *
             FROM POST
             ORDER BY DataPost DESC
             LIMIT 20;'
        );
        if (!$s->execute()) {
            return false;
        }
    } else {
        $s = $dbh->connect()->prepare(
            'SELECT * 
             FROM POST P
             WHERE P.Creatore IN 
                (SELECT F.Followed
                 FROM FOLLOW F
                 WHERE F.Follower = ?)
             OR P.Creatore IN 
                (SELECT A.Amico1
                 FROM AMICIZIA A
                 WHERE A.Amico2 = ?)
             ORDER BY P.DataPost DESC
             LIMIT 20;'
        );
        if (!$s->execute([$username, $username])) {
            return false;
        }
    }
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    $posts = [];
    foreach ($result as $row) {
        $posts[] = array(
            'Creatore' => $row['Creatore'],
            'NrPost' => $row['NrPost'],
            'DataPost' => $row['DataPost'],
            'TestoPost' => $row['TestoPost'],
            'ImmaginePost' => $row['ImmaginePost'],
        );
    }
    return $posts;
}