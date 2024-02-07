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
            <h1>LongLight</h1>
        </header>
        <nav>
            <ul>
                <li id="pag_principale"><a href="home.php">Home</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <li id="pag_profilo"><a id="session_user_name" href="profile.php?id=<?=$_SESSION['NomeUtente']?>"><?=$_SESSION['NomeUtente']?></a></li>
                <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
                <li class="current_page" id="pag_notifiche"><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <?php if (!(empty($notifiche_non_lette) && empty($notifiche_lette))): ?>
                <h2 id="notifications_total"></h2>
                <?php if (!empty($notifiche_non_lette)): ?>
                    <h3 id="unread_total"></h3>
                    <section id="unread_notifications_list">
                        <?php foreach($notifiche_non_lette as $n): ?>
                            <article id="nid<?= $n['IdNotifica']; ?>" name="non_letta">
                                <header><h4>Notifica da <a href="profile.php?id=<?=strval($n['Mandante'])?>"><?= strval($n['Mandante']); ?></a></h4></header>
                                <p><?= strval($n['TestoNotifica']); ?></p>
                                <?php if ($n['Richiesta'] == 1): ?>
                                    <ul class="respond_to_<?=strval($n['Mandante'])?>">
                                        <li><button class="friendrefuse neg" name="friend_refuse">Rifiuta</button></li>
                                        <li><button class="friendaccept pos" name="friend_accept">Accetta</button></li>
                                    </ul>
                                <?php else: ?>
                                    <button class="removenotification neutral" name="note_remove">Rimuovi notifica</button>
                                    <?php if ($n['Richiesta'] > 1): ?>
                                        <input type="hidden" value="<?= strval($n['Richiesta'])?>">
                                        <button class="redirect_post pos">Vai al post</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <button class="readnotification neutral">Segna come letta</button>
                            </article>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
                <?php if (!empty($notifiche_lette)): ?>
                    <h3 id="read_total"></h3>
                    <section id="read_notifications_list">
                        <?php foreach($notifiche_lette as $n): ?>
                            <article id="nid<?= $n['IdNotifica'] ?>" name="letta">
                                <header><h4>Notifica da <a href="profile.php?id=<?=strval($n['Mandante'])?>"><?= strval($n['Mandante']); ?></a></h4></header>
                                <p><?= strval($n['TestoNotifica']); ?></p>
                                <?php if ($n['Richiesta'] == true): ?>
                                    <ul class="respond_to_<?=strval($n['Mandante'])?>">
                                        <li><button class="friendrefuse neg" name="friend_refuse">Rifiuta</button></li>
                                        <li><button class="friendaccept pos" name="friend_accept">Accetta</button></li>
                                    </ul>
                                    <?php else: ?>
                                        <button class="removenotification neutral" name="note_remove">Rimuovi notifica</button>
                                        <?php if ($n['Richiesta'] > 1): ?>
                                            <input type="hidden" value="<?= strval($n['Richiesta'])?>">
                                            <button class="redirect_post pos">Vai al post</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <p>Non hai nessuna notifica</p>
            <?php endif; ?>
        </main>
        <script src="../js/notifiche.js" type="text/javascript"></script>
    </body>
</html>
