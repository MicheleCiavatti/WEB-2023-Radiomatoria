<?php
    if (!isset($_GET['id'])) {
        header('location: home.php?notifications_not_available_without_login');
    }
    require_once './includes/notificationsInfo.inc.php';
    $username = $_GET['id'];
    $notifications = notificationAccess();
    $notifiche_non_lette = $notifications[0];
    $notifiche_lette = $notifications[1];
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Notifiche</title>
        <meta charset="UTF-8"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <li id="pag_profilo"><a href="profile.php?id=<?= $_SESSION['NomeUtente']; ?>"><?= $_SESSION['NomeUtente']; ?></a></li>
                <li><a href="home.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <li><a href="notifiche.php?id=<?= $_SESSION['NomeUtente']; ?>">Notifiche</a></li>
                <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <?php if (!(empty($notifiche_non_lette) && empty($notifiche_lette))): ?>
                <h2 id="notifications_total"></h2>
                <?php if (!empty($notifiche_non_lette)): ?>
                    <section>
                        <h3 id="unread_total"></h3>
                        <ul id="unread_notifications_list">
                            <?php foreach($notifiche_non_lette as $n): ?>
                                <li id="nid<?= $n['IdNotifica']; ?>" name="non_letta">
                                    <header><h4>Notifica da <a href="profile.php?id=<?=strval($n['Mandante'])?>"><?= strval($n['Mandante']); ?></a></h4></header>
                                    <p><?= strval($n['TestoNotifica']); ?></p>
                                    <?php if ($n['Richiesta'] == true): ?>
                                        <button class="friendrefuse" name="friend_refuse">Rifiuta</button>
                                        <button class="friendaccept" name="friend_accept">Accetta</button>
                                    <?php else: ?>
                                        <button class="removenotification" name="note_remove">Rimuovi notifica</button>
                                    <?php endif; ?>
                                    <button class="readnotification">Segna come letta</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
                <?php if (!empty($notifiche_lette)): ?>
                    <section>
                        <h3 id="read_total"></h3>
                        <ul id="read_notifications_list">
                            <?php foreach($notifiche_lette as $n): ?>
                                <li id="nid<?= $n['IdNotifica'] ?>" name="letta">
                                    <header><h4>Notifica da <a href="profile.php?id=<?=strval($n['Mandante'])?>"><?= strval($n['Mandante']); ?></a></h4></header>
                                    <p><?= strval($n['TestoNotifica']); ?></p>
                                    <?php if ($n['Richiesta'] == true): ?>
                                        <button class="removenotification" name="friend_refuse">Rifiuta</button>
                                        <button class="friendaccept" name="friend_accept">Accetta</button>
                                    <?php else: ?>
                                        <button class="removenotification" >Rimuovi notifica</button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <p>Non hai nessuna notifica</p>
            <?php endif; ?>
        </main>
        <script src="../js/notifiche.js" type="text/javascript"></script>
    </body>
</html>
