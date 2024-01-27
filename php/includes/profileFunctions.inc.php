<?php
session_start();
require_once __DIR__ . "/../classes/dbh.classes.php";
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

function removeFreq($frequenza, $username) {
    $dbh = new Dbh;
    $stmt = $dbh->connect()->prepare("DELETE FROM BANDE WHERE MHz = ? AND NomeUtente = ?");
    if(!$stmt->execute(array($frequenza, $username))) {
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