<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Profilo personale</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <?php if (cookiesSet()): ?>
                    <li id="pag_profilo"><a href="accessProfile(readCookie('NomeUtente'))">Profilo</a></li>
                <?php endif; ?>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <?php if (cookiesSet()): ?>
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
                <section>
                    <table>
                        <tr>
                            <td><img src="<?php echo $utente['FotoProfilo'] ?>" alt=""/></td>
                            <td><?php echo $utente["NomeUtente"] ?></td>
                        </tr>
                        <tr>
                            <td>Indirizzo</td>
                            <td><?php echo $utente["Indirizzo"] ?></td>
                        </tr>
                        <tr>
                            <td>Comune</td>
                            <td><?php echo $utente["Citta"] ?></td>
                        </tr>
                        <tr>
                            <td>Data di nascita</td>
                            <td><?php echo $utente["DataNascita"] ?></td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td><?php echo $utente["IndirizzoMail"] ?></td>
                        </tr>
                        <tr>
                            <td>Frequenze preferite (MegaHertz)</td>
                            <td>
                                <ul id="lista_frequenze">
                                    <?php foreach($frequenze as $frequenza): ?>
                                    <li><?php echo $frequenza["MHz"]; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <caption>Orari di presenza in radio</caption>
                                    <tr id="intestazione_orari">
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>11</th>
                                        <th>12</th>
                                    </tr>
                                    <tr id="riga_orari_mattina">
                                        <th>AM</th>
                                        <td headers="1 AM"></td>
                                        <td headers="2 AM"></td>
                                        <td headers="3 AM"></td>
                                        <td headers="4 AM"></td>
                                        <td headers="5 AM"></td>
                                        <td headers="6 AM"></td>
                                        <td headers="7 AM"></td>
                                        <td headers="8 AM"></td>
                                        <td headers="9 AM"></td>
                                        <td headers="10 AM"></td>
                                        <td headers="11 AM"></td>
                                        <td headers="12 AM"></td>
                                    </tr>
                                    <tr id="riga_orari_sera">
                                        <th>PM</th>
                                        <td headers="1 PM"></td>
                                        <td headers="2 PM"></td>
                                        <td headers="3 PM"></td>
                                        <td headers="4 PM"></td>
                                        <td headers="5 PM"></td>
                                        <td headers="6 PM"></td>
                                        <td headers="7 PM"></td>
                                        <td headers="8 PM"></td>
                                        <td headers="9 PM"></td>
                                        <td headers="10 PM"></td>
                                        <td headers="11 PM"></td>
                                        <td headers="12 PM"></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <ul id="lista_orari">
                                    <?php foreach($orari as $intervallo): ?>
                                    <li><?php echo $intervallo["oraInizio"] + "-" + $intervallo["oraFine"]; ?></li>
                                    <?php tabellaOrari($intervallo["oraInizio"].getHours(), $intervallo["oraFine"].getHours());
                                    endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </section>
                <?php if($utente['NomeUtente'] == readCookie('NomeUtente')): ?>
                    <section>
                        <button class="access_required" onclick="modificaProfilo($utente['NomeUtente'])">Inserisci o modifica</button>
                        <form action="alterProfile.php" method="post" name="alter_form">
                            <fieldset>
                            <legend>Campi pubblici</legend>
                            <table>
                                <tr>
                                    <td><label for="user_name">Nome utente</label></td>
                                    <td><input type="text" name="user_name" id="user_name" value="$utente['NomeUtente']"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_photo">Immagine di profilo</label></td>
                                    <td><input type="image" name="user_photo" id="user_photo" src="$utente['FotoProfilo']" alt=""/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_address">Indirizzo (di casa)</label></td>
                                    <td><input type="text" name="user_address" id="user_address" value="$utente['Indirizzo']"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_city">Comune di residenza</label></td>
                                    <td><input type="text" name="user_city" id="user_city" value="$utente['Citta']"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_dob">Data di nascita</label></td>
                                    <td><input type="date" name="user_dob" id="user_dob" value="$utente['DataNascita']"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_mail">Recapito e-mail</label></td>
                                    <td><input type="email" name="user_mail" id="user_mail" value="$utente['IndirizzoMail']"/></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <caption>Frequenze preferite (aggiungere progressivemente in MegaHertz)</caption>
                                            <?php foreach($frequenze as $frequenza): ?>
                                                <tr>
                                                    <td><?php echo $frequenza["MHz"]; ?></td>
                                                    <td><button onclick="removeFreq($frequenza['MHz'])">Rimuovi</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                                <tr>
                                                    <td><label for="user_freq">Aggiungi</label></td>
                                                    <td><input type="number" name="user_freq" step="any" min="0" id="user_freq"/></td>
                                                </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <caption>Orari in radio (non si accettano sovrapposizioni)</caption>
                                            <?php foreach($orari as $intervallo): ?>
                                                <tr>
                                                    <td><?php echo $intervallo["oraInizio"] + "-" + $intervallo["oraFine"]; ?></td>
                                                    <td><button onclick="removeInterval($intervallo['oraInizio'])">Rimuovi</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                                <tr>
                                                    <td><label for="user_time_start">Inizio</label></td>
                                                    <td><input type="time" name="user_time_start" id="user_time_start"/></td>
                                                </tr>
                                                <tr>
                                                    <td><label for="user_time_end">Termine</label></td>
                                                    <td><input type="time" name="user_time_end" id="user_time_end"/></td>
                                                </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend>Campi privati</legend>
                            <table>
                                <tr>
                                    <td><label for="user_clue">Indizio per recupero password</label></td>
                                    <td><input type="text" name="user_clue" id="user_clue" value="<?php echo $utente['Indizio']; ?>"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_passwd0">Vecchia password</label></td>
                                    <td><input type="password" name="user_passwd0" id="user_passwd0" minlength="8"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_passwd1">Nuova password</label></td>
                                    <td><input type="password" name="user_passwd1" id="user_passwd1" minlength="8"/></td>
                                </tr>
                                <tr>
                                    <td><label for="user_passwd2">Conferma nuova password</label></td>
                                    <td><input type="password" name="user_passwd2" id="user_passwd2" minlength="8"/></td>
                                </tr>
                            </table>
                            </fieldset>
                            <input type="reset" value="Annulla" title="Annulla"/>
                            <input type="submit" value="Invia"/>
                        </form>
                    </section>
                <?php endif; ?>
            </header>
            <?php if(cookiesSet() && $utente['NomeUtente'] != readCookie('NomeUtente')): ?>
                <section id="comandi">
                    <h3>Comandi</h3>
                    <?php if(isFriend($utente['NomeUtente'])): ?>
                        <button class="access_required" onclick="removeFriend($utente['NomeUtente'])">Rescindi amicizia</button>
                    <?php else: ?>
                        <button class="access_required" onclick="notify('ti ha inviato una richiesta di amicizia', $utente['NomeUtente'], true)">Richiedi amicizia</button>
                    <?php endif;
                    if(isFollowed($utente['NomeUtente'])): ?>
                        <button class="access_required" onclick="removeFollowed($utente['NomeUtente'])">Lascia</button>
                    <?php else: ?>
                        <button class="access_required" onclick="addFollowed($utente['NomeUtente'])">Segui</button>
                    <?php endif;
                    if(isBlocked($utente['NomeUtente'])): ?>
                        <button class="access_required" onclick="removeBlocked($utente['NomeUtente'])">Rilascia blocco</button>
                    <?php else: ?>
                        <button class="access_required" onclick="addBlocked($utente['NomeUtente'])">Blocca</button>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            <section id="post_column">
                <header>
                    <form action="selectPostProfile.php" method="post" name="select_form_profile">
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
                            <option value="" selected>Seleziona</option>
                            <option value="data">Data</option>
                            <option value="like">Like</option>
                            <option value="comm">Commenti</option>
                        </select>
                        <label for="order">In ordine decrescente</label>
                        <input type="checkbox" name="order" id="order" checked/>
                    </form>
                    <?php if (readCookie('NomeUtente')): ?>
                        <button id="add_post_button" class="access_required" onclick="mostraFormPost()">Aggiungi post</button>
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
                </header>
                <article>
                    <ul>
                        <?php foreach($post_list as $post): ?>
                            <li>
                            <table>
                                <tr>
                                    <td><button onclick="accessProfile($post['Creatore'])"></button><?php echo $post["Creatore"]; ?></td>
                                    <td><?php echo $post["DataPost"]; ?></td>
                                    <?php if ($post["Creatore"] == readCookie('NomeUtente')): ?>
                                        <td><button onclick="removePost($post['NrPost'])" class="access_required">Rimuovi</button></td>
                                    <?php endif; ?>
                                </tr>
                                <tr><td><?php echo $post["TestoPost"]; ?></td></tr>
                                <tr><td><img src="$post['ImmaginePost']" alt=""/></td></tr>
                                <tr>
                                    <td><?php echo $post["LikePost"]; ?></td>
                                    <td><button name="$post['NrPost']_like_button" class="preference_button" onclick="<?php if (readCookie('NomeUtente')): ?>like($post['NrPost'])<?php endif; ?>">Like</button></td>
                                    <td><?php echo $post["DislikePost"]; ?></td>
                                    <td><button name="$post['NrPost']_dislike_button" class="preference_button" onclick="<?php if (readCookie('NomeUtente')): ?>dislike($post['NrPost'])<?php endif; ?>">Dislike</button></td>
                                    <?php if (readCookie('NomeUtente')): ?>
                                        <td><button id="add_comment_button" class="access_required" onclick="mostraFormCommenti($post['NrPost'], $post['Creatore'], $post['DataPost'], '')">Commenta</button></td>
                                    <?php endif; ?>
                                </tr>
                                <tr><td><button onclick="mostraCommentiPost($post['NrPost'])">Mostra commenti</button></td></tr>
                            </table>
                            <ul id="<?php echo $post['NrPost']; ?>_comment_list">
                                <?php 
                                    $query = "SELECT COMMENTI.*, COUNT(CASE WHEN INTERAZIONE.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT INTERAZIONE.Tipo THEN 1 END) AS DislikeCommento,
                                    FROM COMMENTI LEFT JOIN INTERAZIONI ON COMMENTI.NrCommento = INTERAZIONI.ElementId WHERE COMMENTI.NrPost = ? ORDER BY COMMENTI.DataCommento DESC";
                                    $stmt = $this->db->prepare($query);
                                    $stmt->bind_param('i', $post['NrPost']);
                                    $stmt->execute();
                                    $commenti = $stmt->get_result();
                                    foreach($commenti as $commento):
                                ?>
                                    <li>
                                        <table>
                                            <tr>
                                                <td><button onclick="accessProfile($commento['Creatore'])"><?php echo $commento["Creatore"]; ?></button></td>
                                                <td><?php echo $commento["DataCommento"]; ?></td>
                                                <?php if($commento['Creatore'] == readCookie('NomeUtente')): ?>
                                                    <td><button onclick="removeComment($commento['NrCommento'])" class="access_required">Rimuovi</button></td>
                                                <?php endif; ?>
                                            </tr>
                                            <tr><td><?php echo $commento["TestoCommento"]; ?></td></tr>
                                            <tr><td><img src="$commento['ImmagineCommento']" alt=""/></td></tr>
                                            <tr>
                                                <td><?php echo $commento["LikeCommento"]; ?></td>
                                                <td><button id="$commento['NrCommento']_like_button" class="preference_button"
                                                onclick="<?php if (readCookie('NomeUtente')): ?>like($commento['NrCommento'])<?php endif; ?>">Like</button></td>
                                                <td><?php echo $commento["DislikeCommento"]; ?></td>
                                                <td><button id="$commento['NrCommento']_dislike_button" class="preference_button"
                                                onclick="<?php if (readCookie('NomeUtente')): ?>dislike($commento['NrCommento'])<?php endif; ?>">Dislike</button></td>
                                                <?php if readCookie('NomeUtente'): ?>
                                                    <td><button onclick="mostraFormCommenti($post['NrPost'], $post['Creatore'], $post['DataPost'], '@' + $commento['Creatore'])">Rispondi</button></td>
                                                <?php endif; ?>
                                            </tr>
                                        </table>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (cookiesSet()): ?>
                                <form action="addComment.php" method="post" name="add_comment_form">
                                    <table>
                                        <tr>
                                            <td id="comment_post_info"></td>
                                            <td><input type="hidden" name="post_id_input" id="post_id_input"/></td>
                                        </tr>
                                        <tr>
                                            <td><label for="comment_img">Inserisci immagine (opzionale)</label></td>
                                            <td><input type="image" name="comment_img" id="comment_img" alt=""/></td>
                                        </tr>
                                        <tr>
                                            <td><label for="comment_text">Inserisci testo</label></td>
                                            <td><textarea name="comment_text" id="comment_text" required></textarea></td>
                                        </tr>
                                        <tr>
                                            <td><input type="reset" value="Annulla"/></td>
                                            <td><input type="submit" value="Scrivi"/></td>
                                        </tr>
                                    </table>
                                </form>
                            <?php endif; ?>
                            </li>
                        <?php endforeach;
                        decorate($element_id_like, $element_id_dislike); ?>
                    </ul>
                </article>
            </section>
            <aside>
                <h2>Amici</h2>
                <ul>
                    <?php foreach($amici as $amico): ?>
                        <li><img src="$amico['FotoProfilo']" alt=""/></li>
                        <li><button onclick="accessProfile($amico['Amico2'])"><?php echo $amico["NomeUtente"] ?></button></li>
                        <?php if($utente['NomeUtente'] == readCookie('NomeUtente')): ?>
                            <li><button class="access_required" onclick="removeFriend($amico['Amico2'])">Rimuovi</button></li>
                        <?php endif;
                    endforeach; ?>
                </ul>
                <h2>Seguiti</h2>
                <ul>
                    <?php foreach($seguiti as $seguito): ?>
                        <li><img src="$seguito['FotoProfilo']" alt=""/></li>
                        <li><button onclick="accessProfile($seguito['Followed'])"><?php echo $seguito["NomeUtente"] ?></button></li>
                        <?php if($utente['NomeUtente'] == readCookie('NomeUtente')): ?>
                            <li><button class="access_required" onclick="removeFollowed($seguito['Followed'])">Rimuovi</button></li>
                        <?php endif;
                    endforeach; ?>
                </ul>
                <?php if($utente['NomeUtente'] == readCookie('NomeUtente')): ?>
                    <h3>Bloccati</h2>
                    <ul>
                        <?php foreach($bloccati as $bloccato): ?>
                            <li><img src="$bloccato['FotoProfilo']" alt=""/></li>
                            <li><button onclick="accessProfile($bloccato['Bloccato'])"><?php echo $bloccato["Bloccato"] ?></button></li>
                            <li><button class="access_required" onclick="removeBlocked($bloccato['Bloccato'])">Perdona</button></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </aside>
        </main>
        <script src="js/profile.js" type="text/javascript"></script>
        <script src="js/index.js" type="text/javascript"></script>
        <script src="js/generale.js" type="text/javascript"></script>
    </body>
</html>
