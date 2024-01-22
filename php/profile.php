<?php
    require_once './includes/profileInfo.inc.php';
    $post_list = [];
    $element_id_like = [];
    $element_id_dislike = [];
    $username = $_GET['id']; //Get user owner of the profile
    $data = profileAccess($username); 
    $utente = $data[0]; //Contains user info
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Profilo di <?= $utente['NomeUtente']?></title>
        <meta charset="UTF-8"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <li>
                    <?php if (isset($_SESSION['NomeUtente'])): ?>
                        <li id="pag_profilo"><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <?php endif; ?>
                </li>
                <li><a href="home.php">Home page</a></li>
                <li><a href="guide.php">Guida</a></li>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <li><a href="notifiche.php">Notifiche</a></li>
                    <li><a href="./php/includes/logout.inc.php">Logout</a></li>
                <?php else: ?>
                    <li id="pag_creazione"><a href="signup.html">Crea account</a></li>
                    <li id="pag_accesso"><a href="login.html">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <header>
                <img src="<?= $utente['FotoProfilo'] ?>" alt=""/>
                <p><?= $utente["NomeUtente"] ?></p>
            </header>
            <section>
                <ul>
                    <li>Indirizzo: <?php echo $utente['Indirizzo']?></li>
                    <li>Città: <?php echo $utente['Città']?></li>
                    <li>Data di Nascita: <?php echo $utente['DataNascita']?></li>
                    <li>Indirizzo e-mail: <?php echo $utente['IndirizzoMail']?></li>
                </ul>
            </section>
            <!--HANDLING USER FREQUENCIES: both retrieving and inserting-->
            <section>
                <ul>
                    <?php
                        foreach($frequenze as $f):
                    ?>
                    <li id="f<?= $f?>"><?= $f ?></li>
                    <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                        <button type="button" onclick="removeFrequency('<?= $f?>', '<?= $utente['NomeUtente']?>')">Rimuovi</button>
                    <?php endif; ?>
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
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
