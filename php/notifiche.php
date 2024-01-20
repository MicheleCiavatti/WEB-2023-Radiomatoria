<?php
session_start();
if (!cookiesSet())
    header('location: login.html?error=needtologin');
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Notifiche</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body onload="list_notifications()">
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <li id="pag_profilo"><a href="accessProfile(readCookie('NomeUtente'))">Profilo</a></li>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <li id="pag_notifiche"><a href="notifiche.php">Notifiche </a></li>
                <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <h2 id="notifications_total">Notifiche totali: </h2>
            <section>
                <h3>Da leggere</h3>
                <ul id="unread_notifications_list">
                    <?php foreach($notifiche_non_lette as $notifica): ?>
                        <li onclick="readNotification($notifica['IdNotifica'])">
                            <button onclick="accessProfile($notifica['Mandante'])"><?php echo $notifica['MittenteNotifica']; ?></button>
                            <span><?php echo $notifica['TestoNotifica']; ?></span>
                            <?php if($notifica['Richiesta'] == true): ?>
                                <button name="friend_refuse" onclick="outcomeNotification($notifica['IdNotifica'], $notifica['Mandante'], 'ha rifiutato la tua richiesta di amicizia')">Rifiuta</button>
                                <button name="friend_accept" onclick="addFriend($notifica['IdNotifica'], $notifica['Mandante'])">Accetta</button>
                            <?php else: ?>
                                <button onclick="removeNotification($notifica['IdNotifica'])">Rimuovi</button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section>
                <h3>Lette</h3>
                <ul id="read_notifications_list">
                    <?php foreach($notifiche_lette as $notifica): ?>
                        <li>
                            <button onclick="accessProfile($notifica['Mandante'])"><?php echo $notifica['MittenteNotifica']; ?></button>
                            <span><?php echo $notifica['TestoNotifica']; ?></span>
                            <?php if($notifica['Richiesta'] == true): ?>
                                <button name="friend_refuse" onclick="outcomeNotification($notifica['IdNotifica'], $notifica['Mandante'], 'ha rifiutato la tua richiesta di amicizia')">Rifiuta</button>
                                <button name="friend_accept" onclick="addFriend($notifica['IdNotifica'], $notifica['Mandante'])">Accetta</button>
                            <?php else: ?>
                                <button onclick="removeNotification($notifica['IdNotifica'])">Rimuovi</button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
        <script src="js/notifiche.js" type="text/javascript"></script>
        <script src="js/generale.js" type="text/javascript"></script>
    </body>
</html>
