<?php
require_once './includes/profileInfo.inc.php';
if (!(isset($_SESSION['NomeUtente']) && isset($_SESSION['FotoProfilo'])))
    header('location: ../../login.html?error=needtologin');
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Profile</title>
        <meta charset="UTF-8"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <aside>
        <img src="<?php echo $_SESSION['FotoProfilo']; ?>" alt=""/>
        <p><?php echo $_SESSION['NomeUtente']; ?></p>
        </aside>
        <nav>
            <ul>
                <li><a href="#">Profilo</a></li>
                <li><a href="home.php">Home page</a></li>
                <li><a href="guide.html">Guida</a></li>
                <li><a href="includes/logout.inc.php">Logout</a></li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <h2><?php echo $_SESSION['NomeUtente']?>'s info</h2>
                <ul>
                    <li><?php echo $_SESSION['Indirizzo']?></li>
                    <li><?php echo $_SESSION['Città']?></li>
                    <li><?php echo $_SESSION['DataNascita']?></li>
                    <li><?php echo $_SESSION['IndirizzoMail']?></li>
                </ul>
            </section>
            <!--HANDLIING USER FREQUENCIES: both retrieving and inserting-->
            <section>
                <h2><?php echo $_SESSION['NomeUtente']?>'s frequencies</h2>
                <ul>
                    <?php 
                        getFrequencies($_SESSION['NomeUtente']);
                        foreach($_SESSION['Frequenze'] as $f):
                    ?>
                    <li><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
                <form action="includes/addMHz.inc.php" method="post">
                    <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" type="number" step="any" min="0" required></label>
                    <input type="submit" value="Aggiungi">
                </form>
            </section>
            <!--HANDLING USER TIME SLOTS: both retrieving and inserting-->
            <section>
                <h2><?php echo $_SESSION['NomeUtente']?>'s time slots</h2>
                <ul>
                    <?php
                        getTimeSlots($_SESSION['NomeUtente']);
                        foreach($_SESSION['Orari'] as $t):
                    ?>
                    <li><?= $t[0] ?> - <?= $t[1] ?></li>
                    <?php endforeach; ?>
                </ul>
                <form action="includes/addTimeSlot.inc.php" method="post">
                    <label for="orainizio">OraInizio:<input name="orainizio" type="time" required></label>
                    <label for="orafine">OraFine:<input name="orafine" type="time" required></label>
                    <input type="submit" value="Aggiungi">
                </form>
            </section>
        </main>
    </body>
</html>
