<?php
    require_once './includes/profileInfo.inc.php';
    $post_list = [];
    $element_id_like = [];
    $element_id_dislike = [];
    $owner = $_GET['id']; //Get user owner of the profile
    $data = profileAccess($owner); 
    /*Adding user's info in local variables*/
    $utente = $data[0]; //Contains user info
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
/*    if(!isset($_SESSION['NomeUtente'])) {
        $_SESSION['NomeUtente'] = "AlessandroC";//TEST!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    }*/
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Profilo di <?= $owner ?></title>
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
                    <li id="pag_profilo"><a href="profile.php?id=<?= $_SESSION['NomeUtente']; ?>">Profilo</a></li>
                <?php endif; ?>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <li id="pag_notifiche"><a href="notifiche.php">Notifiche</a></li>
                    <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
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
                <?php if (isset($_SESSION['NomeUtente']) && $owner != $_SESSION['NomeUtente']): ?>
                    <ul id="comandi">
                        <li id="session_user_name"><?= $_SESSION['NomeUtente']?></li> <!--- Hidden field containing session user name --->
                        <li>
                                <?php if (isFriend($_SESSION['NomeUtente'], $owner)): ?>
                                    <button name="remove_friend" type="button" class="access_required">Rescindi amicizia</button>
                                <?php else: ?>
                                    <button id="friend_request" type="button" class="access_required">Richiedi amicizia</button>
                                <?php endif; ?>
                        </li>
                        <li>
                                <?php if (isFollowed($_SESSION['NomeUtente'], $owner)): ?>
                                    <button name="remove_follow" type="button" class="access_required">Rimuovi follow</button>
                                <?php else: ?>
                                    <button id="follow_button" type="button" class="access_required">Segui</button>
                                <?php endif; ?>
                        </li>
                        <li>
                                <?php if (isBlocked($_SESSION['NomeUtente'], $owner)): ?>
                                    <button name="remove_block" type="button" class="access_required">Rilascia blocco</button>
                                <?php else: ?>
                                    <button id="add_block" type="button" class="access_required">Blocca</button>
                                <?php endif; ?>
                        </li>
                    </ul>
                <?php endif; ?>
            </header>
            <section>
                <ul>
                    <li>Indirizzo: <?php echo $utente['Indirizzo']?></li>
                    <li>Città: <?php echo $utente['Città']?></li>
                    <li>Data di Nascita: <?php echo $utente['DataNascita']?></li>
                    <li>Indirizzo e-mail: <?php echo $utente['IndirizzoMail']?></li>
                </ul>
            </section>
            <!--************************************* HANDLING USER FREQUENCIES **************************************-->
            <section>
                <ul>
                    <?php
                        foreach($frequenze as $f):
                    ?>
                    <!-- Frequency displaying and removing done via AJAX -->
                    <li id="f<?= str_replace('.', '_', $f)?>">
                        <?= $f ?>
                        <?php if ($_SESSION['NomeUtente'] == $owner):?>
                            <button class="access_required" name="remove_frequency_buttons" type="button" value="<?= $f ?>">Rimuovi</button>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Form for adding frequencies -->
                <?php if ($_SESSION['NomeUtente'] == $owner):?>
                    <form action="includes/addMHz.inc.php" method="post">
                        <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" type="number" step="any" min="0" required></label>
                        <input type="submit" value="Aggiungi">
                    </form>
                <?php endif; ?>
            </section>
            <!--************************************* HANDLING USER TIME SLOTS **************************************-->
            <section>
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
                <ul>
                    <?php
                        foreach($orari as $intervallo):
                    ?>
                    <!-- Time slots displaying and removing done via AJAX -->
                    <li id="ts<?= str_replace(':', '_', $intervallo[0] . $intervallo[1])?>">
                        <?= $intervallo[0] ?> - <?= $intervallo[1]?>
                        <?php if ($_SESSION['NomeUtente'] == $owner):?>
                            <button class="access_required" name="remove_timeslot_buttons" type="button">Rimuovi</button> 
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Form for adding time slots -->
                <?php if ($_SESSION['NomeUtente'] == $owner):?>
                    <span>Non si accettano sovrapposizioni né segmentazioni (fasce orarie divise in segmenti immediatamente consecutivi)</span>
                    <form action="includes/addTimeSlot.inc.php" method="post">
                        <label for="orainizio">OraInizio:<input name="orainizio" type="time" required></label>
                        <label for="orafine">OraFine:<input name="orafine" type="time" required></label>
                        <input type="submit" value="Aggiungi">
                    </form>
                <?php endif; ?>
            </section>
            <!--************************************* HANDLING PASSWORD AND CLUE **************************************-->
            <?php if($owner == $_SESSION['NomeUtente']): ?>
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
            <!--************************************* POST COLUMN ****************************************************-->
            <section>
            <form action="selectPostProfile.php" method="post" name="select_form_profile" id="select_form">
                <input type="hidden" name="username" id="username" value="<?= $owner ?>"/>
                <label for="relation">Seleziona post in base alla relazione col proprietario del profilo</label>
                <select name="relation" id="relation" onchange="this.form.submit()">
                    <option value="none" selected>Nessuna</option>
                    <option value="create" >Creati</option>
                    <option value="like">Apprezzati</option>
                    <option value="dislike">Disapprovati</option>
                    <option value="comment">Commentati</option>
                </select>
                <label for="sort">Ordina per</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="none" selected>Seleziona</option>
                    <option value="data">Data</option>
                    <option value="like">Like</option>
                    <option value="comm">Commenti</option>
                </select>
                <label for="order">In ordine decrescente</label>
                <input type="checkbox" name="order" id="order" checked/>
            </form>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <button id="add_post_button" class="access_required">Aggiungi post</button>
                <form action="addPost.php" method="post" name="add_post_form">
                    <table>
                        <tr>
                            <td><label for="post_img">Inserisci immagine (opzionale)</label></td>
                            <td><input type="image" name="post_img" id="post_img" alt=""/></td>
                        </tr>
                        <tr>
                            <td><label for="post_text">Inserisci testo</label></td>
                            <td><textarea name="post_text" id="post_text" required></textarea></td>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Annulla"/></td>
                            <td><input type="submit" value="Scrivi"/></td>
                        </tr>
                    </table>
                </form>
            <?php endif; ?>
            </section>
            <article>
                <ul id="post_list">
                </ul>
            </article>
        </main>
        <aside>
            <section>
                <h2>Amici</h2>
                <ul>
                    <?php foreach($amici as $amico):?>
                        <li id="<?= $amico[0] ?>">
                            <img src="<?= '../' . $amico[1] ?>" alt=""/>
                            <a href="profile.php?id=<?= $amico[0]?>"><?= $amico[0] ?></a>
                            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                <button class="access_required" name="remove_friend">Rimuovi</button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach;?>
                </ul>
            </section>
            <section>
                <h2>Following</h2>
                <ul>
                    <?php foreach($seguiti as $seguito):?>
                        <?php error_log($seguito[1]); ?>
                        <li id="<?= $seguito[0] ?>">
                            <img src="<?= '../' . $seguito[1] ?>" alt=""/>
                            <a href="profile.php?id=<?= $seguito[0]?>"><?= $seguito[0] ?></a>
                            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                <button class="access_required" name="remove_follow">Rimuovi</button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach;?>
                </ul>
            </section>
            <section>
                <?php if($owner == $_SESSION['NomeUtente']): ?>
                    <h3>Bloccati</h2>
                    <ul>
                        <?php foreach($bloccati as $bloccato): ?>
                            <li id="<?= $bloccato[0] ?>">
                                <img src="<?= $bloccato[1]; ?>" alt=""/>
                                <a href="profile.php?id=<?= $bloccato[0]; ?>)"><?= $bloccato[0]; ?></a>
                                <button class="access_required" name="remove_block">Rimuovi</button>
                            </li>
                            
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </aside>
        <script src="../js/profile.js" type="text/javascript"></script>
        <script src="../js/index.js" type="text/javascript"></script>
    </body>
</html>
