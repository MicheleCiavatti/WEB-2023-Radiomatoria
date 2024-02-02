<?php
    session_start();
    if (!isset($_GET['id'])) {
        header('location: home.php?notifications_not_available_without_login');
    }
    require_once './includes/notificationsInfo.inc.php';
    $username = $_GET['id'];
    $notifications = notificationAccess($username);
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
                <li><a href="home.php">Home</a></li>
                <li><a href="guida.php">Guida</a></li>
                <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                <li><a href="includes/logout.inc.php">Logout</a></li>
                <li class="current_page"><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            
            <header><h2>Le tue notifiche</h2></header>
            <p id="session_user_name"><?= $_SESSION['NomeUtente']; ?></p> <!-- Hidden field containing session user name-->
            <?php if (!empty($notifications)): ?>
                <section>
                    <?php foreach ($notifications as $n): ?>
                        <section>
                            <header><h3>Notifica da <a href="profile.php?id=<?=strval($n['Mandante'])?>"><?= strval($n['Mandante']); ?></a></h3></header>
                            <p><?= strval($n['TestoNotifica']); ?></p>
                            <?php if ($n['Richiesta'] == true): ?>
                                <ul>
                                    <li><button class="friendrefuse" name="friend_refuse">Rifiuta</button></li>
                                    <li><button class="friendaccept" name="friend_accept">Accetta</button></li>
                                </ul>
                            <?php else: ?>
                                <!--- TODO: add button to remove notification -->
                                <button class="removenotification" >Rimuovi notifica</button>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                </section>
            <?php else: ?>
                <p>Non hai nessuna notifica</p>
            <?php endif; ?>
            <?php /*
            <h2 id="notifications_total">Notifiche totali: </h2>
            <section>
                <h3>Da leggere</h3>
                <ul id="unread_notifications_list">
                    <?php foreach($notifiche_non_lette as $notifica): ?>
                        <li id="nid<?= $notifica['IdNotifica'] ?>" onclick="readNotification($notifica['IdNotifica'])">
                            <a href="profile.php?id=<?= $notifica['Mandante'] ?>"><?= $notifica['Mandante']; ?></a>
                            <span><?= $notifica['TestoNotifica']; ?></span>
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
                            <a href="profile.php?id=<?= $notifica['Mandante'] ?>"><?= $notifica['Mandante']; ?></a>
                            <span><?= $notifica['TestoNotifica']; ?></span>
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
            */ ?>
        </main>
        <script src="../js/notifiche.js" type="text/javascript"></script>
    </body>
</html>
