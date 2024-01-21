<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Home page</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <aside>
            <?php 
                session_start();
                if(isset($_COOKIE['NomeUtente'])):
            ?>
                <img id="FotoProfilo" src="<?= $_COOKIE['FotoProfilo'] ?>" alt=""/>
                <a id="NomeUtente" href="profile.php?id=<?= $_COOKIE['NomeUtente']; ?>"><?= $_COOKIE['NomeUtente']; ?></a>
            <?php endif; ?>
        </aside>
        <nav>
            <ul>
                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                    <li id="pag_profilo"><a href="profile.php?id=<?= $_COOKIE['NomeUtente']; ?>">Profilo</a></li>
                <?php endif; ?>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                    <li id="pag_notifiche"><a href="notifiche.php">Notifiche</a></li>
                    <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
                <?php else: ?>
                    <li id="pag_creazione"><a href="signup.php">Crea account</a></li>
                    <li id="pag_accesso"><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <header>
                <section>
                    <form action="selectPostHome.php" method="post" name="select_form_home">
                        <?php if(isset($_COOKIE['NomeUtente'])): ?>
                            <label for="origin">Seleziona post in base all'autore</label>
                            <select name="origin" id="origin">
                                <option value="all" selected>Tutti</option>
                                <option value="strangers" >Sconosciuti</option>
                                <option value="friends">Amici</option>
                                <option value="followed">Seguiti</option>
                                <option value="mine">Me stesso</option>
                            </select>
                        <?php endif; ?>
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
                    <?php if(isset($_COOKIE['NomeUtente'])): ?>
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
                </section>
            </header>
            <article>
                <ul onload="decorate($element_id_like, $element_id_dislike)">
                    <?php foreach($post_list as $post): ?>
                    <li>
                        <table>
                            <tr>
                                <td><a href="profile.php?id=<?= $post['Creatore']; ?>)"></a><?= $post["Creatore"]; ?></td>
                                <td><?= $post["DataPost"]; ?></td>
                                <?php if ($post["Creatore"] == $_COOKIE['NomeUtente']): ?>
                                    <td><button onclick="removePost($post['NrPost'])" class="access_required">Rimuovi</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><?= $post["TestoPost"]; ?></td></tr>
                            <tr><td><img src="<?= $post['ImmaginePost']; ?>" alt=""/></td></tr>
                            <tr>
                                <td><?= $post["LikePost"]; ?></td>
                                <td><button name="<?= $post['NrPost']; ?>_like_button" class="preference_button" onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>like(<?= $post['NrPost'] ?>)<?php endif; ?>">Like</button></td>
                                <td><?= $post["DislikePost"]; ?></td>
                                <td><button name="<?= $post['NrPost']; ?>_dislike_button" class="preference_button" onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>dislike(<?= $post['NrPost'] ?>)<?php endif; ?>">Dislike</button></td>
                                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                                    <td><button id="add_comment_button" class="access_required" onclick="mostraFormCommenti($post['NrPost'], $post['Creatore'], $post['DataPost'], '')">Commenta</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><button onclick="mostraCommentiPost(<?= $post['NrPost']; ?>)">Mostra commenti</button></td></tr>
                        </table>
                        <ul id="<?= $post['NrPost']; ?>_comment_list">
                            <?php 
                                $query = "SELECT COMMENTI.*, COUNT(CASE WHEN INTERAZIONI.Tipo THEN 1 END) AS LikeCommento, COUNT(CASE WHEN NOT INTERAZIONE.Tipo THEN 1 END) AS DislikeCommento,
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
                                            <td><a href="profile.php?id=<?= $commento['Creatore']; ?>)"><?= $commento["Creatore"]; ?></a></td>
                                            <td><?= $commento["DataCommento"]; ?></td>
                                            <?php if($commento['Creatore'] == $_COOKIE['NomeUtente']): ?>
                                                <td><button onclick="removeComment(<?= $commento['NrCommento']; ?>)" class="access_required">Rimuovi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                        <tr><td><?= $commento["TestoCommento"]; ?></td></tr>
                                        <tr><td><img src="<?= $commento['ImmagineCommento']; ?>" alt=""/></td></tr>
                                        <tr>
                                            <td><?= $commento["LikeCommento"]; ?></td>
                                            <td><button id="<?= $commento['NrCommento']; ?>_like_button" class="preference_button"
                                            onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>like(<?= $commento['NrCommento']; ?>)<?php endif; ?>">Like</button></td>
                                            <td><?= $commento["DislikeCommento"]; ?></td>
                                            <td><button id="<?= $commento['NrCommento']; ?>_dislike_button" class="preference_button"
                                            onclick="<?php if(isset($_COOKIE['NomeUtente'])): ?>dislike(<?= $commento['NrCommento']; ?>)<?php endif; ?>">Dislike</button></td>
                                            <?php if(isset($_COOKIE['NomeUtente'])): ?>
                                                <td><button onclick="mostraFormCommenti($post['NrPost'], $post['Creatore'], $post['DataPost'], '@' + $commento['Creatore'])">Rispondi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if(isset($_COOKIE['NomeUtente'])): ?>
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
                    <?php endforeach; ?>
                </ul>
            </article>
        </main>
        <script src="js/index.js" type="text/javascript"></script>
    </body>
</html>
