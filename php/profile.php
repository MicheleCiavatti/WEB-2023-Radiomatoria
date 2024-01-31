<?php
    require_once './includes/profileInfo.inc.php';
    $username = $_GET['id']; //Get user owner of the profile
    $data = profileAccess($username); 
    /*Adding user's info in local variables*/
    $utente = $data[0]; 
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
    $post_list = $data[6];
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
                <?php else: ?>
                    <form action="includes/changeProfilePic.inc.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_image" accept=".jpg, .jpeg, .png" required>
                        <input type="submit" name="upload_propic" value="Cambia immagine">
                    </form>
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
            <?php if(!empty($frequenze) || $_SESSION['NomeUtente'] == $utente['NomeUtente']): ?>
                <section>
                    <header><h2>Frequenze</h2></header>
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
            <?php endif; ?>
            <!--************************************* HANDLING USER TIME SLOTS **************************************-->
            <?php if(!empty($orari) || $_SESSION['NomeUtente'] == $utente['NomeUtente']): ?>
                <section>
                    <header><h2>Orari</h2></header>
                    <ul>
                        <?php foreach($orari as $intervallo): ?>
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
            <?php endif; ?>
            <!--************************************* HANDLING PASSWORD AND CLUE **************************************-->
            <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                <section>
                    <header><h2>Modifica password e indizio</h2></header>
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
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $amico[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $amico[0]?>"><?= $amico[0] ?></a>
                                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="remove_friend_buttons">Rimuovi</button>
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
                                <li>
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $seguito[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $seguito[0]?>"><?= $seguito[0] ?></a>
                                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="remove_follow_buttons" >Rimuovi</button>
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
                                        <button class="access_required" >Perdona</button>
                                    <?php endif; ?>
                                </li>
                                
                            <?php endforeach; ?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING POSTS **************************************-->
            <section>
                    <header><h2>Post</h2></header>
                    <?php if($utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                    <p>
                        <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="post_image" accept=".jpg, .jpeg, .png">
                            <textarea name="post_text" rows="4" cols="50" placeholder="Scrivi un post" required></textarea>
                            <input type="submit" name="upload_post" value="Pubblica">
                        </form>
                    </p>
                <?php endif; ?>
                <?php foreach($post_list as $post): ?>
                    <article class="post">
                        <header>
                            <?php if ($post[4] != null): ?>
                                <img src="<?= $post[4]; ?>" alt=""/>
                            <?php endif; ?>
                            <p><a href="profile.php?id=<?= $post[0]; ?>"><?= $post[0]; ?></a> <?= $post[2]; ?></p>
                        </header>
                        <section><?= $post[3]; ?></section>
                        <section>
                            <ul>
                                <?php 
                                    $comments = getComments($post[0], $post[1]);
                                    foreach($comments as $comment):
                                ?>
                                <li>
                                    <php if($comment[3] != null): ?>
                                        <img src="<?=strval($comment[3]); ?>" alt=""/>
                                    <p><strong><a href="profile.php?id=<?=strval($comment[0]);?>"><?=strval($comment[0]);?></a></strong> <?= strval($comment[1]);?></p>
                                    <p><?=strval($comment[2]);?></p>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="comment_image" accept=".jpg, .jpeg, .png">
                                <textarea name="comment_text" rows="1" cols="100" placeholder="Rispondi al post di <?= $post[0]?>" required></textarea>
                                <input type="hidden" name="post_author" value="<?= $post[0]?>">
                                <input type="hidden" name="post_number" value="<?= $post[1]?>">
                                <input type="submit" value="Pubblica">
                            </form>
                        </section>
                    </article>
                <?php endforeach; ?>
            </section>
        </main>
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
