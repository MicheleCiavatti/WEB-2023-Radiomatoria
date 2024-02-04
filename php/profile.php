<?php
    require_once './includes/profileInfo.inc.php';
    $username = $_GET['id']; //Get owner of the profile
    $data = profileAccess($username); 
    /*Adding user's info in local variables*/
    $utente = $data[0]; 
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
    $post_list = $data[6];
    $n_notifications = isset($_SESSION['NomeUtente']) ? getNotifications($_SESSION['NomeUtente']) : 0;
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
            <h1>LongLight</h1>
        </header>
        <!-- Nav differs if user logged in or not -->
        <?php if (!isset($_SESSION['NomeUtente'])): ?> <!-- If user is not logged in -->
            <nav class="nav4">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="guida.php">Guida</a></li>
                    <li><a href="../signup.html">Signup</a></li>
                    <li><a href="../login.html">Login</a></li>
                </ul>
            </nav>
        <?php else: ?> <!-- If user is logged in -->
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="guida.php">Guida</a></li>
                    <li <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']) echo 'class="current_page"'; ?>><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <li><a href="includes/logout.inc.php">Logout</a></li>
                    <?php if ($n_notifications == 0): ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                    <?php else: ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche<sup>(<?= $n_notifications; ?>)</sup></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <main>
            <header>
                <!--- Profile pic, name and buttons for friendship/follow --->
                <img src="<?= $utente['FotoProfilo'] ?>" alt=""/>
                <p id='profile_name'><?= $utente["NomeUtente"] ?></p>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <?php if ($utente['NomeUtente'] != $_SESSION['NomeUtente']): ?>
                        <ul>
                            <li id="session_user_name"><?= $_SESSION['NomeUtente']?></li> <!--- Hidden field containing session user name --->
                            <li>
                                    <?php if (isFriend($_SESSION['NomeUtente'], $utente['NomeUtente'])): ?>
                                        <button id="remove_friend" type="button" value="Rimuovi amicizia">Rimuovi amicizia</button>
                                    <?php else: ?>
                                        <?php if (friendshipRequested($_SESSION['NomeUtente'], $utente['NomeUtente'])): ?>
                                            <button id="cancel_request" type="button" value="Annulla richiesta">Annulla richiesta</button>
                                        <?php else: ?>
                                            <button id="add_friend" type="button" value="Richiedi amicizia">Richiedi amicizia</button>
                                        <?php endif; ?>
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
                            <label class="label_invisible">Nuova immagine profilo<input type="file" name="profile_image" accept=".jpg, .jpeg, .png" required></label>
                            <input type="submit" name="upload_propic" value="Cambia immagine">
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </header>
            <aside>
                <ul>
                    <li>Città: <?php echo $utente['Città']?></li>
                    <li>Data di Nascita: <?php echo $utente['DataNascita']?></li>
                    <li>Indirizzo e-mail: <?php echo $utente['IndirizzoMail']?></li>
                </ul>
            </aside>
            <!--************************************* HANDLING USER FREQUENCIES **************************************-->
            <!-- The user is not logged in or the user is not the owner of the profile -->
            <?php if (!isset($_SESSION['NomeUtente']) || $_SESSION['NomeUtente'] != $utente['NomeUtente']): ?>
                <?php if (!empty($frequenze)): ?>
                    <section>
                        <header><h2>Frequenze</h2></header>
                        <ul>
                            <?php foreach($frequenze as $f): ?>
                            <li><?= $f ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <!-- The user is logged in and owner of the profile -->
                <section>
                    <header><h2>Frequenze</h2></header>
                    <ul>
                        <?php foreach($frequenze as $f): ?>
                        <li id="f<?= str_replace('.', '_', $f)?>" class="remove_frequency_buttons">
                            <?= $f ?>
                            <button type="button" value="<?= $f ?>">Rimuovi</button>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Form for adding frequencies -->
                    <form action="includes/addMHz.inc.php" method="post">
                        <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" id="frequency" type="number" step="any" min="0" required></label>
                        <input type="submit" value="Aggiungi">
                    </form>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING USER TIME SLOTS **************************************-->
            <!-- The user is not logged in or the user is not the owner of the profile -->
            <?php if (!isset($_SESSION['NomeUtente']) || $_SESSION['NomeUtente'] != $utente['NomeUtente']): ?>
                <?php if (!empty($orari)): ?>
                    <section>
                        <header><h2>Orari</h2></header>
                        <ul>
                            <?php foreach($orari as $intervallo): ?>
                            <li><?= $intervallo[0] ?> - <?= $intervallo[1]?></li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <!-- The user is logged in and owner of the profile -->
                <section>
                    <header><h2>Orari</h2></header>
                    <ul>
                        <?php foreach($orari as $intervallo): ?>
                            <li id="ts<?= str_replace(':', '_', $intervallo[0] . $intervallo[1])?>" class="remove_timeslot_buttons">
                                <?= $intervallo[0] ?> - <?= $intervallo[1]?>
                                <button type="button">Rimuovi</button> 
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Form for adding time slots -->
                    <?php if ($_SESSION['NomeUtente'] == $utente['NomeUtente']):?>
                        <span>Non si accettano sovrapposizioni né segmentazioni (fasce orarie divise in segmenti immediatamente consecutivi)</span>
                        <form action="includes/addTimeSlot.inc.php" method="post">
                            <label for="orainizio">OraInizio:<input name="orainizio" id="orainizio" type="time" required></label>
                            <label for="orafine">OraFine:<input name="orafine" id="orafine" type="time" required></label>
                            <input type="submit" value="Aggiungi">
                        </form>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING PASSWORD AND CLUE **************************************-->
            <?php if(isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                <section>
                    <header><h2>Modifica password e indizio</h2></header>
                    <ul>
                        <li>Indizio: <?= $utente['Indizio']?></li>
                    </ul>
                    <form action="includes/changeClue.inc.php" method="post">
                        <label for="new_clue">Modifica l'indizio: <input name="new_clue" id="new_clue" required></label>
                        <input type="submit" value="Modifica indizio">
                    </form>
                    <form action="includes/changePW.inc.php" method="post">
                        <label for="new_pw">Cambia password:<input name="new_pw" type="password" id="new_pw" minlength="8" required></label>
                        <input type="submit" value="Modifica password">
                    </form>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING FRIEND LIST **************************************-->
            <?php if(!empty($amici)): ?>
                <section>
                    <header><h2>Amici</h2></header>
                    <p>
                        <ul>
                            <?php foreach($amici as $amico):?>
                                <li>
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $amico[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $amico[0]?>"><?= $amico[0] ?></a>
                                    <?php if(isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
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
                    <header><h2>Following</h2></header>
                    <p>
                        <ul>
                            <?php foreach($seguiti as $seguito):?>
                                <li>
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $seguito[1] ?>" alt=""/>
                                    <a href="profile.php?id=<?= $seguito[0]?>"><?= $seguito[0] ?></a>
                                    <?php if(isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                                        <button class="remove_follow_buttons" >Rimuovi</button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING BLOCKED LIST **************************************-->
            <?php if(isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente'] && !empty($bloccati)): ?>
                <section>
                    <header><h2>Bloccati</h2></header>
                    <p>
                        <ul>
                            <?php foreach($bloccati as $bloccato): ?>
                                <li>
                                    <img src="<?= $bloccato[1]; ?>" alt=""/>
                                    <a href="profile.php?id=<?= $bloccato[0]; ?>)"><?= $bloccato[0]; ?></a>
                                    <?php if(isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
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
                    <?php if (isset($_SESSION['NomeUtente']) && $utente['NomeUtente'] == $_SESSION['NomeUtente']): ?>
                        <p>
                            <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                                <label>Immagine post<input type="file" name="post_image" accept=".jpg, .jpeg, .png"></label>
                                <label>Testo post<textarea name="post_text" rows="4" cols="50" placeholder="Scrivi un post" required></textarea></label>
                                <input type="submit" name="upload_post" value="Pubblica">
                            </form>
                        </p>
                    <?php endif; ?>
                <?php if (empty($post_list)): ?>
                    <p><?= $utente['NomeUtente'] ?> non ha alcun post.</p>
                <?php else: ?>
                    <?php foreach($post_list as $post): ?>
                        <article class="post">
                            <header>
                                <p><a href="profile.php?id=<?= $post[0]; ?>"><?= $post[0]; ?></a></p>
                                <p><?= $post[2]; ?></p>
                                <?php if ($post[4] != null): ?>
                                    <img src="<?= $post[4]; ?>" alt=""/>
                                <?php endif; ?>
                            </header>
                            <section>
                                <p><?= $post[3]; ?></p>
                                <!-- like/un-like button -->
                                <?php if (isset($_SESSION['NomeUtente'])): ?>
                                    <footer>Likes: <?= getLikes($post[0], $post[1]) ?></footer>
                                <?php endif; ?>
                            </section>
                            <section>
                                <?php if (isset($_SESSION['NomeUtente'])): ?>
                                    <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data">
                                        <label>Immagine commento<input type="file" name="comment_image" accept=".jpg, .jpeg, .png"></label>
                                        <label>Testo commento<textarea name="comment_text" rows="1" cols="100" placeholder="Rispondi al post di <?= $post[0]?>" required></textarea></label>
                                        <input type="hidden" name="post_author" value="<?= $post[0]?>">
                                        <input type="hidden" name="post_number" value="<?= $post[1]?>">
                                        <input type="submit" value="Pubblica">
                                    </form>
                                <?php endif; ?>
                                <ul>
                                    <?php 
                                        $comments = getComments($post[0], $post[1]);
                                        foreach($comments as $comment):
                                    ?>
                                    <li>
                                        <p><a href="profile.php?id=<?=strval($comment[0]);?>"><?=strval($comment[0]);?></a> <?= strval($comment[1]);?></p>
                                        <p><?=strval($comment[2]);?></p>
                                        <?php if($comment[3] != null): ?>
                                            <img src="<?=strval($comment[3]); ?>" alt=""/>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </section>
                        </article>
                    <?php endforeach; ?>
            <?php endif; ?>
            </section>
        </main>
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
