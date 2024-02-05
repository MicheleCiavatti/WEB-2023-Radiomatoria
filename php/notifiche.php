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
                                        <li><button class="friendrefuse neg" name="friend_refuse">Rifiuta</button></li>
                                        <li><button class="friendaccept pos" name="friend_accept">Accetta</button></li>
                                    </ul>
                            <?php else: ?>
                                <!--- TODO: add button to remove notification -->
                                <input type="hidden" name="notification_id" value="<?= strval($n['IdNotifica']); ?>">
                                <button class="removenotification neutral">Rimuovi notifica</button>
                            <?php endif; ?>
                        </section>
                    <?php endforeach; ?>
                </section>
            <?php else: ?>
                <section>Non hai nessuna notifica</section>
            <?php endif; ?>
        </main>
        <script src="../js/notifiche.js"></script>
    </body>
</html>
