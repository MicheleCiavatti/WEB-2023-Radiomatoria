<?php
    require_once './includes/profileInfo.inc.php';
    $username = $_GET['id']; //Get user owner of the profile
    if(isset($_GET['relation'])) {
        $relation = $_GET['relation'];
        $sort = $_GET['sort'];
        $order = $_GET['order'];
    } else {
        $relation = "none";
        $sort = "data";
        $order = 1;
    }
    $data = profileAccess($username); 
    /*Adding user's info in local variables*/
    $utente = $data[0]; 
    $frequenze = $data[1]; 
    $orari = $data[2]; 
    $amici = $data[3]; 
    $seguiti = $data[4]; 
    $bloccati = $data[5];
    $data = selectPostProfile($username, $relation, $sort, $order);
    $post_list = $data[0];
    $element_id_like = $data[1];
    $element_id_dislike = $data[2];
    if(!isset($_SESSION['NomeUtente'])) {
        $_SESSION['NomeUtente'] = null;
    }
    $commentable = false;
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Profilo di <?= $username?></title>
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
                <?php if ($username != $_SESSION['NomeUtente']): ?>
                    <ul id="comandi">
                        <li id="session_user_name"><?= $_SESSION['NomeUtente']?></li> <!--- Hidden field containing session user name --->
                        <li>
                                <?php if (isFriend($username)): ?>
                                    <button class="access_required" name="remove_friend_buttons">Rescindi amicizia</button>
                                <?php else: ?>
                                    <button class="access_required" id="friend_request">Richiedi amicizia</button>
                                <?php endif; ?>
                        </li>
                        <li>
                                <?php if (isFollowed($username)): ?>
                                    <button class="access_required" name="remove_follow_buttons">Rimuovi follow</button>
                                <?php else: ?>
                                    <button class="access_required" id="follow_button">Segui</button>
                                <?php endif; ?>
                        </li>
                        <li>
                                <?php if (isBlocked($username)): ?>
                                    <button class="access_required" name="remove_block_buttons">Solleva blocco</button>
                                <?php else: ?>
                                    <button class="access_required" id="block_button">Blocca</button>
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
            <!--************************************* HANDLING PUBLIC SINGULAR FIELDS **************************************-->
            <aside id="public_fields">
                <ul>
                    <li>Indirizzo: <?= $utente['Indirizzo']; ?></li>
                    <li>Città: <?= $utente['Città']; ?></li>
                    <li>Data di Nascita: <?= $utente['DataNascita']; ?></li>
                    <li>Indirizzo e-mail: <?= $utente['IndirizzoMail']; ?></li>
                </ul>
                <?php if ($username == $_SESSION['NomeUtente']): ?>
                    <button class="access_required" id="change_public_fields">Modifica campi pubblici</button>
                    <form action="includes/alter_profile.inc.php" method="post" id="change_fields_form">
                        <table>
                        <tr>
                                <td><label for="new_address">Nome utente: </label></td>
                                <td><input type="text" name="new_name" id="new_name" value="<?= $utente['NomeUtente']; ?>" required/></td>
                            </tr>
                            <tr>
                                <td><label for="new_address">Indirizzo: </label></td>
                                <td><input type="text" name="new_address" id="new_address" value="<?= $utente['Indirizzo']; ?>" required/></td>
                            </tr>
                            <tr>
                                <td><label for="new_city">Città: </label></td>
                                <td><input type="text" name="new_city" id="new_city" value="<?= $utente['Città']; ?>" required/></td>
                            </tr>
                            <tr>
                                <td><label for="new_dob">Data di Nascita: </label></td>
                                <td><input type="date" name="new_dob" id="new_dob" value="<?= $utente['DataNascita']; ?>" required/></td>
                            </tr>
                            <tr>
                                <td><label for="new_mail">Indirizzo e-mail: </label></td>
                                <td><input type="mail" name="new_mail" id="new_mail" value="<?= $utente['IndirizzoMail']; ?>" required/></td>
                            </tr>
                            <tr>
                                <td><input type="reset" value="Annulla"></td>
                                <td><input type="submit" value="Aggiorna campi"></td>
                            </tr>
                        </table>
                    </form>
                <?php endif; ?>
            </aside>
            <!--************************************* HANDLING USER FREQUENCIES **************************************-->
            <?php if(!empty($frequenze) || $_SESSION['NomeUtente'] == $username): ?>
                <section>
                    <header><h2>Frequenze</h2></header>
                    <ul>
                        <?php
                            foreach($frequenze as $f):
                        ?>
                        <!-- Frequency displaying and removing done via AJAX -->
                        <li id="f<?= str_replace('.', '_', $f)?>" class="frequencies">
                            <?= $f ?>
                            <?php if ($_SESSION['NomeUtente'] == $username):?>
                                <button class="access_required" name="remove_frequency_buttons" type="button" value="<?= $f ?>">Rimuovi</button>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Form for adding frequencies -->
                    <?php if ($_SESSION['NomeUtente'] == $username):?>
                        <form action="includes/addMHz.inc.php" method="post">
                            <label for="frequency">Nuova frequenza (in MHz):<input name="frequency" id="frequency" type="number" step="any" min="0" required></label>
                            <input type="submit" value="Aggiungi">
                        </form>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING USER TIME SLOTS **************************************-->
                <section>
                    <header><h2>Orari</h2></header>
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
                    </section>
                    <?php if(!empty($orari) || $_SESSION['NomeUtente'] == $username): ?>
                        <ul>
                            <?php foreach($orari as $intervallo): ?>
                            <!-- Time slots displaying and removing done via AJAX -->
                            <li id="ts<?= str_replace(':', '_', $intervallo[0] . $intervallo[1])?>" class="timeslots">
                                <?= $intervallo[0] ?> - <?= $intervallo[1]?>
                                <?php if ($_SESSION['NomeUtente'] == $username):?>
                                    <button class="access_required" name="remove_timeslot_buttons" type="button">Rimuovi</button> 
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <!-- Form for adding time slots -->
                        <?php if ($_SESSION['NomeUtente'] == $username):?>
                            <span>Non si accettano sovrapposizioni né segmentazioni (fasce orarie divise in segmenti immediatamente consecutivi)</span>
                            <form action="includes/addTimeSlot.inc.php" method="post">
                                <label for="orainizio">OraInizio:<input name="orainizio" id="orainizio" type="time" required></label>
                                <label for="orafine">OraFine:<input name="orafine" id="orafine" type="time" required></label>
                                <input type="submit" value="Aggiungi">
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
            <!--************************************* HANDLING PASSWORD AND CLUE **************************************-->
            <?php if($username == $_SESSION['NomeUtente']): ?>
                <button class="access_required" id="change_private_fields">Modifica campi privati</button>
                <section id="private_fields">
                    <header><h2>Modifica password e indizio</h2></header>
                    <span>Indizio: <?= $utente['Indizio']?></span>
                    <form action="includes/changeClue.inc.php" method="post">
                        <label for="new_clue">Modifica l'indizio: <input name="new_clue" id="new_clue" required/></label>
                        <input type="submit" value="Modifica indizio">
                    </form>
                    <span>Password: <?= $utente['Password']?></span>
                    <form action="includes/changePW.inc.php" method="post">
                        <label for="new_pw1">Cambia password (almeno 8 caratteri):<input name="new_pw1" type="password" id="new_pw1" minlength="8" required></label>
                        <label for="new_pw2">Conferma nuova password:<input name="new_pw2" type="password" id="new_pw2" minlength="8" required></label>
                        <input type="submit" value="Modifica password">
                    </form>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING POSTS **************************************-->
            <section>
                <form action="includes/selectPostProfile.php" method="post" name="select_form_profile" id="select_form">
                    <input type="hidden" name="username" id="username" value="<?= $utente['NomeUtente'] ?>"/>
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
            </section>
            <section id="post_column">
                <header>
                    <h2>Post</h2>
                    <?php if(isset($_SESSION['NomeUtente'])): ?>
                        <button id="add_post_button" class="access_required">Aggiungi post</button>
                        <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data" id="add_post_form">
                            <input type="file" name="post_image" accept=".jpg, .jpeg, .png">
                            <textarea name="post_text" rows="4" cols="50" placeholder="Scrivi un post" required></textarea>
                            <input type="submit" name="upload_post" value="Pubblica">
                        </form>
                    <?php endif; ?>
                </header>
                <?php
                    if(!empty($post_list)):
                        foreach($post_list as $post):
                ?>
                    <article class="post" id="post<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">
                        <header>
                            <?php if ($post['ImmaginePost'] != null): ?>
                                <img src="<?= $post['ImmaginePost']; ?>" alt=""/>
                            <?php endif; ?>
                            <p><a href="profile.php?id=<?= $post['Creatore']; ?>"><?= $post['Creatore']; ?></a> <?= $post['DataPost']; ?></p>
                        </header>
                        <section>
                            <p><?= $post['TestoPost']; ?></p>
                            <table>
                                <tr>
                                    <td id="like_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0"><?= $post['LikePost']; ?></td>
                                    <td><button id="like_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" class="preference_button" name="like_button">Like</button></td>
                                </tr>
                                <tr>
                                    <td id="dislike_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0"><?= $post['DislikePost']; ?></td>
                                    <td><button id="dislike_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" class="preference_button" name="dislike_button">Dislike</button></td>
                                </tr>
                            </table>
                            <?php if(isset($_SESSION['NomeUtente'])): ?>
                                <?php if($post['Creatore'] != $_SESSION['NomeUtente']):
                                    $commentable = true; ?>
                                    <button class="access_required" name="comment_post" id="comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">Commenta</button>
                                <?php else: ?>
                                    <button class="access_required" name="remove_post" id="remove_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">Rimuovi</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </section>
                        <section>
                            <?php
                                $comments = getComments($post['Creatore'], $post['NrPost']);
                                if(!empty($comments)):
                            ?>
                                <button id="show_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>" name="show_comments">Mostra commenti</button>
                                <ul id="<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_comment_list" name="comment_list">
                                    <?php foreach($comments as $comment): ?>
                                    <li class="comment" id="comment<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>">
                                        <?php if($comment['ImmagineCommento'] != null): ?>
                                            <img src="<?=strval($comment['ImmagineCommento']); ?>" alt=""/>
                                        <?php endif; ?>
                                        <p><strong><a href="profile.php?id=<?=strval($comment['AutoreCommento']);?>"><?=strval($comment['AutoreCommento']);?></a></strong> <?= strval($comment['NrCommento']);?></p>
                                        <p><?=strval($comment['TestoCommento']);?></p>
                                        <table>
                                            <tr>
                                                <td id="like_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>"><?= $post['LikeCommento']; ?></td>
                                                <td><button id="like_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>" class="preference_button" name="like_button">Like</button></td>
                                            </tr>
                                            <tr>
                                                <td id="dislike_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>"><?= $post['DislikeCommento']; ?></td>
                                                <td><button id="dislike_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>" class="preference_button" name="dislike_button">Dislike</button></td>
                                            </tr>
                                        </table>
                                        <footer>
                                            <?php if(isset($_SESSION['NomeUtente'])): ?>
                                                <?php if($comment['AutoreCommento'] != $_SESSION['NomeUtente']):
                                                    $commentable = true; ?>
                                                    <button class="access_required" name="answer_comment" id="comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>">Rispondi a <?= $comment['AutoreCommento']; ?></button>
                                                <?php else: ?>
                                                    <button class="access_required" name="remove_comment" id="remove_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>">Rimuovi</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </footer>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </section>
                    </article>
                <?php
                        endforeach;
                    endif;?>
                <footer>
                    <?php if(isset($_SESSION['NomeUtente']) && ($commentable)): ?>
                        <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data" id="add_comment_form">
                            <input type="file" name="comment_image" accept=".jpg, .jpeg, .png">
                            <textarea name="comment_text" rows="2" cols="50" required></textarea>
                            <input type="hidden" name="post_author"/>
                            <input type="hidden" name="post_number"/>
                            <input type="reset" value="Annulla commento" id="comment_reset">
                            <input type="submit" value="Pubblica">
                        </form>
                    <?php endif; ?>
                </footer>
            </section>
            <!--************************************* HANDLING FRIEND LIST **************************************-->
            <?php if(!empty($amici)): ?>
                <section>
                    <header><h2>Amici</h2><header>
                    <p>
                        <ul>
                            <?php foreach($amici as $amico):?>
                                <li id="<?= $amico[0]; ?>">
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $amico[1]; ?>" alt=""/>
                                    <a href="profile.php?id=<?= $amico[0]; ?>"><?= $amico[0]; ?></a>
                                    <?php if($username == $_SESSION['NomeUtente'] || $amico[0] == $_SESSION['NomeUtente']): ?>
                                        <button class="access_required" name="remove_friend_buttons">Rimuovi</button>
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
                                <li id="<?= $seguito[0]; ?>">
                                    <img src="<?= "http://localhost/WEB-2023-Radiomatoria/img/" . $seguito[1]; ?>" alt=""/>
                                    <a href="profile.php?id=<?= $seguito[0]; ?>"><?= $seguito[0]; ?></a>
                                    <?php if($username == $_SESSION['NomeUtente']): ?>
                                        <button class="access_required" name="remove_follow_buttons">Rimuovi</button>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
            <!--************************************* HANDLING BLOCKED LIST **************************************-->
            <?php if($username == $_SESSION['NomeUtente'] && !empty($bloccati)): ?>
                <section>
                    <header><h2>Bloccati</h2></header>
                    <p>
                        <ul>
                            <?php foreach($bloccati as $bloccato): ?>
                                <li id="<?= $bloccato[0]; ?>">
                                    <img src="<?= $bloccato[1]; ?>" alt=""/>
                                    <a href="profile.php?id=<?= $bloccato[0]; ?>)"><?= $bloccato[0]; ?></a>
                                    <button class="access_required"  name="remove_block_buttons">Perdona</button>
                                </li>
                                
                            <?php endforeach; ?>
                        </ul>
                    </p>
                </section>
            <?php endif; ?>
        </main>
        <script src="../js/profile.js" type="text/javascript"></script>
    </body>
</html>
