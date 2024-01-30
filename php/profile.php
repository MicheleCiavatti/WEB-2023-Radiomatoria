<?php
    require_once './includes/profileInfo.inc.php';
    $post_list = [];
    $element_id_like = [];
    $element_id_dislike = [];
    $username = $_GET['id']; //Get user owner of the profile
    $data = profileAccess($username); 
    /*Adding user's info in local variables*/
    $utente = $data[0]; 
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Profilo di <?= $utente['NomeUtente']?></title>
        <meta charset="UTF-8"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <li id="pag_profilo"><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                <?php endif; ?>
                <li><a href="home.php">Home page</a></li>
                <li><a href="guida.php">Guida</a></li>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <li><a href="notifiche.php">Notifiche</a></li>
                    <li><a href="./includes/logout.inc.php">Logout</a></li>
                <?php else: ?>
                    <li id="pag_creazione"><a href="signup.html">Crea account</a></li>
                    <li id="pag_accesso"><a href="login.html">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <header>
                <!--- Profile pic, name and buttons for friendship/follow --->
                <img src="<?= $utente['FotoProfilo'] ?>" alt=""/>
                <p id='profile_name'><?= $utente["NomeUtente"] ?></p>
                <?php if ($utente['NomeUtente'] != $_SESSION['NomeUtente']): ?>
                <ul>
                    <li id="session_user_name"><?= $_SESSION['NomeUtente']?></li> <!--- Hidden field containing session user name --->
                    <li>
                            <?php if (isFriend($_SESSION['NomeUtente'], $utente['NomeUtente'])): ?>
                                <button id="remove_friend" type="button" value="Rimuovi amicizia">Rimuovi amicizia</button>
                            <?php else: ?>
                                <button type="button" value="Richiedi amicizia">Richiedi amicizia</button>
                            <?php endif; ?>
                    </li>
                    <li>
                            <?php if (isFollowed($_SESSION['NomeUtente'], $utente['NomeUtente'])): ?>
                                <button id="remove_follow" type="button">Rimuovi follow</button>
                            <?php else: ?>
                                <button id="follow_button" type="button">Segui</button>
                            <?php endif; ?>
                    </li>
                </ul>
                <?php endif; ?>
            </header>
            <aside>
                <ul>
                    <li>Indirizzo: <?php echo $utente['Indirizzo']?></li>
                    <li>Città: <?php echo $utente['Città']?></li>
                    <li>Data di Nascita: <?php echo $utente['DataNascita']?></li>
                    <li>Indirizzo e-mail: <?php echo $utente['IndirizzoMail']?></li>
                </ul>
            </aside>
            <!--************************************* HANDLING USER FREQUENCIES **************************************-->
            <section>
                <ul>
                    <?php
                        foreach($frequenze as $f):
                    ?>
                    <!-- Frequency displaying and removing done via AJAX -->
                    <li id="f<?= str_replace('.', '_', $f)?>" class="remove_frequency_buttons">
                        <?= $f ?>
                        <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                            <button type="button" value="<?= $f ?>">Rimuovi</button>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Form for adding frequencies -->
                <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                    <form action="includes/addMHz.inc.php" method="post">
                        <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" type="number" step="any" min="0" required></label>
                        <input type="submit" value="Aggiungi">
                    </form>
                <?php endif; ?>
            </section>
            <!--************************************* HANDLING USER TIME SLOTS **************************************-->
            <section>
            <!--
            <table>
                <caption>Orari di presenza in radio</caption>
                <tr id="intestazione_orari">
                    <th></th>
                    <th colspan="2">1</th>
                    <th colspan="2">2</th>
                    <th colspan="2">3</th>
                    <th colspan="2">4</th>
                    <th colspan="2">5</th>
                    <th colspan="2">6</th>
                    <th colspan="2">7</th>
                    <th colspan="2">8</th>
                    <th colspan="2">9</th>
                    <th colspan="2">10</th>
                    <th colspan="2">11</th>
                    <th colspan="2">12</th>
                </tr>
                <tr id="riga_orari_mattina">
                    <th>AM</th>
                    <td headers="1 AM"></td>
                    <td headers="1 AM"></td>
                    <td headers="2 AM"></td>
                    <td headers="2 AM"></td>
                    <td headers="3 AM"></td>
                    <td headers="3 AM"></td>
                    <td headers="4 AM"></td>
                    <td headers="4 AM"></td>
                    <td headers="5 AM"></td>
                    <td headers="5 AM"></td>
                    <td headers="6 AM"></td>
                    <td headers="6 AM"></td>
                    <td headers="7 AM"></td>
                    <td headers="7 AM"></td>
                    <td headers="8 AM"></td>
                    <td headers="8 AM"></td>
                    <td headers="9 AM"></td>
                    <td headers="9 AM"></td>
                    <td headers="10 AM"></td>
                    <td headers="10 AM"></td>
                    <td headers="11 AM"></td>
                    <td headers="11 AM"></td>
                    <td headers="12 AM"></td>
                    <td headers="12 AM"></td>
                </tr>
                <tr id="riga_orari_sera">
                    <th>PM</th>
                    <td headers="1 PM"></td>
                    <td headers="1 PM"></td>
                    <td headers="2 PM"></td>
                    <td headers="2 PM"></td>
                    <td headers="3 PM"></td>
                    <td headers="3 PM"></td>
                    <td headers="4 PM"></td>
                    <td headers="4 PM"></td>
                    <td headers="5 PM"></td>
                    <td headers="5 PM"></td>
                    <td headers="6 PM"></td>
                    <td headers="6 PM"></td>
                    <td headers="7 PM"></td>
                    <td headers="7 PM"></td>
                    <td headers="8 PM"></td>
                    <td headers="8 PM"></td>
                    <td headers="9 PM"></td>
                    <td headers="9 PM"></td>
                    <td headers="10 PM"></td>
                    <td headers="10 PM"></td>
                    <td headers="11 PM"></td>
                    <td headers="11 PM"></td>
                    <td headers="12 PM"></td>
                    <td headers="12 PM"></td>
                </tr>
            </table>
                -->
                <ul>
                    <?php
                        foreach($orari as $intervallo):
                    ?>
                    <!-- Time slots displaying and removing done via AJAX -->
                    <li id="ts<?= str_replace(':', '_', $intervallo[0] . $intervallo[1])?>" class="remove_timeslot_buttons">
                        <?= $intervallo[0] ?> - <?= $intervallo[1]?>
                        <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                            <button type="button">Rimuovi</button> 
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Form for adding time slots -->
                <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                    <span>Non si accettano sovrapposizioni né segmentazioni (fasce orarie divise in segmenti immediatamente consecutivi)</span>
                    <form action="includes/addTimeSlot.inc.php" method="post">
                        <label for="orainizio">OraInizio:<input name="orainizio" type="time" required></label>
                        <label for="orafine">OraFine:<input name="orafine" type="time" required></label>
                        <input type="submit" value="Aggiungi">
                    </form>
                <?php endif; ?>
            </section>
            <!--************************************* HANDLING PASSWORD AND CLUE **************************************-->
            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                <section>
                    <ul>
                        <li>Indizio: <?= $utente['Indizio']?></li>
                    </ul>
                    <form action="includes/changeClue.inc.php" method="post">
                        <label for="new_clue">Modifica l'indizio: <input name="new_clue" required></label>
                        <input type="submit" value="Modifica indizio">
                    </form>
                    <form action="includes/changePW.inc.php" method="post">
                        <label for="new_pw">Cambia password:<input name="new_pw" type="password" minlength="8" required></label>
                        <input type="submit" value="Modifica password">
                    </form>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING FRIEND LIST **************************************-->
            <?php if(!empty($amici)): ?>
                <section>
                    <header><h2>Amici</h2><header>
                    <p>
                        <ul>
                            <?php foreach($amici as $amico):?>
                                <li>
                                    <img src="<?= '../' . $amico[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $amico[0]?>"><?= $amico[0] ?></a>
                                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="remove_friend_buttons" id="remove_followed_button">Rimuovi</button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING FOLLOWED LIST **************************************-->
            <?php if(!empty($seguiti)): ?>
                <section>
                    <header><h2>Following</h2><header>
                    <p>
                        <ul>
                            <?php foreach($seguiti as $seguito):?>
                                <?php error_log($seguito[1]); ?>
                                <li>
                                    <img src="<?= '../' . $seguito[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $seguito[0]?>"><?= $seguito[0] ?></a>
                                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="remove_follow_buttons" id="remove_followed_button">Rimuovi</button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING BLOCKED LIST **************************************-->
            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente'] && !empty($bloccati)): ?>
                <section>
                    <header><h2>Bloccati</h2></header>
                    <p>
                        <ul>
                            <?php foreach($bloccati as $bloccato): ?>
                                <li>
                                    <img src="<?= $bloccato[1]; ?>" alt=""/>
                                    <a href="profile.php?id=<?= $bloccato[0]; ?>)"><?= $bloccato[0]; ?></a>
                                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="access_required" id="remove_block_button">Perdona</button>
                                    <?php endif; ?>
                                </li>
                                
                            <?php endforeach; ?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING POSTS **************************************-->
            <section>
                <p>
                    <form action="includes/addPost.inc.php" method="post">
                        <label for="post_text">Scrivi un post:
                            <textarea name="post_text" rows="4" cols="50" required></textarea>
                        </label>
                        <input type="submit" value="Pubblica">
                    </form>
                </p>
            </section>
        </main>
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
