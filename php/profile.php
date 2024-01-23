<?php
    require_once './includes/profileInfo.inc.php';
    $post_list = [];
    $element_id_like = [];
    $element_id_dislike = [];
    $username = $_GET['id']; //Get user owner of the profile
    $data = profileAccess($username); 
    /*Adding user's info in local variables*/
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
            <!--HANDLING USER FREQUENCIES: both inserting and removing-->
            <section>
                <ul>
                    <?php
                        foreach($frequenze as $f):
                    ?>
                    <!-- Frequency displaying and removing done via AJAX -->
                    <li id="f<?= str_replace('.', '_', $f)?>">
                        <?= $f ?>
                        <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                            <button type="button" onclick="removeFrequency('<?= $f?>', '<?= $utente['NomeUtente']?>', '<?= '#f' . str_replace('.', '_', $f)?>')">Rimuovi</button>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Form for adding frequencies -->
                <form action="includes/addMHz.inc.php" method="post">
                    <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" type="number" step="any" min="0" required></label>
                    <input type="submit" value="Aggiungi">
                </form>
            </section>
            <!-- HANDLING USER TIME SLOTS: both inserting and removing -->
            <section>
                <ul>
                    <?php
                        foreach($orari as $intervallo):
                    ?>
                    <!-- Time slots displaying and removing done via AJAX -->
                    <li id="ts<?= str_replace(':', '_', $intervallo[0] . $intervallo[1])?>">
                        <?= $intervallo[0] ?> - <?= $intervallo[1]?>
                        <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                            <button type="button" onclick="removeTimeInterval('<?= $utente['NomeUtente']?>', '<?= $intervallo[0]?>', '<?= $intervallo[1]?>', '<?= '#ts' . str_replace(':', '_', $intervallo[0] . $intervallo[1])?>')">Rimuovi</button> 
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                    <!-- Form for adding time slots -->
                <form action="includes/addTimeSlot.inc.php" method="post">
                    <label for="orainizio">OraInizio:<input name="orainizio" type="time" required></label>
                    <label for="orafine">OraFine:<input name="orafine" type="time" required></label>
                    <input type="submit" value="Aggiungi">
                </form>
            </section>
            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                <section>
                    <ul>
                        <li>Indizio: <?= $utente['Indizio']?></li>
                    </ul>
                    <form action="includes/changeClue.inc.php" method="post">
                        <label for="new_clue">Nuovo indizio:<input name="new_clue" required></label>
                        <input type="submit" value="Modifica indizio">
                    </form>
                </section>
            <?php endif; ?>
        </main>
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
