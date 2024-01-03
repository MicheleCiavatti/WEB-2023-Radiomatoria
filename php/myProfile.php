<?php
session_start();
if (!(isset($_SESSION['NomeUtente']) && isset($_SESSION['FotoProfilo'])))
    header('location: ../../login.html?error=needtologin');
require_once './includes/profileInfo.inc.php';
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
                <h2><?php echo $_SESSION['NomeUtente']?>' info</h2>
                <ul>
                    <li><?php echo $_SESSION['Indirizzo']?></li>
                    <li><?php echo $_SESSION['Città']?></li>
                    <li><?php echo $_SESSION['DataNascita']?></li>
                    <li><?php echo $_SESSION['IndirizzoMail']?></li>
                </ul>
            </section>
            <section>
                <h2><?php echo $_SESSION['NomeUtente']?>' frequencies</h2>
                <ul>
                    <?php 
                        getFrequencies($_SESSION['NomeUtente']);
                        foreach($_SESSION['Frequenze'] as $f):
                    ?>
                    <li><?= $f ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </body>
</html>
