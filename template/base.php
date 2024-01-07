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
                if(isset($_SESSION['uid']) && isset($_SESSION['img'])):
            ?>
                <img src="<?php echo $_SESSION['img']; ?>" alt=""/>
                <p><?php echo $_SESSION['uid']; ?></p>
            <?php endif; ?>
        </aside>
        <nav>
            <ul>
                <?php if (isset($_SESSION['uid'])): ?>
                    <li id="pag_profilo"><a href="accessProfile($_SESSION['uid'])">Profilo</a></li>
                <?php endif; ?>
                <li id="pag_principale"><a href="index.html">Home page</a></li>
                <li id="pag_guida"><a href="guida.html">Guida</a></li>
                <?php if (isset($_SESSION['uid'])): ?>
                    <li id="pag_notifiche"><a href="notifiche.html">Notifiche</a></li>
                    <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
                <?php else: ?>
                    <li id="pag_creazione"><a href="create.html">Crea account</a></li>
                    <li id="pag_accesso"><a href="login.html">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <header>
                <section>
                    <form action="sortPost.php" method="post" name="sort_form">
                        <label for="sort">Ordina per</label>
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="" selected>Seleziona</option>
                            <option value="data">Data</option>
                            <option value="like">Like</option>
                            <option value="comm">Commenti</option>
                        </select>
                    </form>
                    <?php if (isset($_SESSION['uid'])): ?>
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
                <ul>
                    <?php foreach($templateParams["post"] as $post): ?>
                    <li>
                        <table>
                            <tr>
                                <td><button onclick="accessProfile()"></button><?php echo $post["UserPost"]; ?></td>
                                <td><?php echo $post["DataPost"]; ?></td>
                                <?php if ($post["UserPost"] == $_SESSION['uid']): ?>
                                    <td><button onclick="removePost($post['NrPost'])" class="access_required">Rimuovi</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><?php echo $post["TestoPost"]; ?></td></tr>
                            <tr><td><img src="$post['ImmaginePost']" alt=""/></td></tr>
                            <tr>
                                <td><?php echo $post["LikePost"]; ?></td>
                                <td><button name="$post['NrPost']_like_button" class="preference_button" onclick="<?php if (isset($_SESSION['uid'])): ?>like($post['NrPost'])<?php endif; ?>">Like</button></td>
                                <td><?php echo $post["DislikePost"]; ?></td>
                                <td><button name="$post['NrPost']_dislike_button" class="preference_button" onclick="<?php if (isset($_SESSION['uid'])): ?>dislike($post['NrPost'])<?php endif; ?>">Dislike</button></td>
                                <?php if (isset($_SESSION['uid'])): ?>
                                    <td><button id="add_comment_button" class="access_required" onclick="mostraFormCommenti($post['NrPost'], $post['UserPost'], $post['DataPost'], '')">Commenta</button></td>
                                <?php endif; ?>
                            </tr>
                            <tr><td><button onclick="mostraCommentiPost($post['NrPost'])">Mostra commenti</button></td></tr>
                        </table>
                        <ul id="$post['NrPost']_comment_list">
                            <?php foreach($templateparams["commenti"] as $commento): ?>
                                <li>
                                    <table>
                                        <tr>
                                            <td><button onclick="accessProfile($commento['UserCommento'])"><?php echo $commento["UserCommento"]; ?></button></td>
                                            <td><?php echo $commento["DataCommento"]; ?></td>
                                            <?php if($commento['UserCommento'] == $_SESSION['uid']): ?>
                                                <td><button onclick="removeComment($commento['NrCommento'])" class="access_required">Rimuovi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                        <tr><td><?php echo $commento["TestoCommento"]; ?></td></tr>
                                        <tr><td><img src="$commento['ImmagineCommento']" alt=""/></td></tr>
                                        <tr>
                                            <td><?php echo $commento["LikeCommento"]; ?></td>
                                            <td><button id="$commento['NrCommento']_like_button" class="preference_button"
                                            onclick="<?php if (isset($_SESSION['uid'])): ?>like($commento['NrCommento'])<?php endif; ?>">Like</button></td>
                                            <td><?php echo $commento["DislikeCommento"]; ?></td>
                                            <td><button id="$commento['NrCommento']_dislike_button" class="preference_button"
                                            onclick="<?php if (isset($_SESSION['uid'])): ?>dislike($commento['NrCommento'])<?php endif; ?>">Dislike</button></td>
                                            <?php if (isset($_SESSION['uid'])): ?>
                                                <td><button onclick="mostraFormCommenti($post['NrPost'], $post['UserPost'], $post['DataPost'], '@' + $commento['UserCommento'])">Rispondi</button></td>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (isset($_SESSION['uid'])): ?>
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
