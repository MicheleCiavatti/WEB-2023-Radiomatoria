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
                <li><a href="#">Profilo</a></li>
                <li><a href="index.html">Home page</a></li>
                <li><a href="guida.html">Guida</a></li>
                <li><a href="create.html">Crea account</a></li>
                <li>
                    <?php if (isset($_SESSION['uid'])): ?>
                        <a href="includes/logout.inc.php">Logout</a>
                    <?php else: ?>
                        <a href="login.html">Login</a>
                    <?php endif; ?>
                </li>
                <li><a href="notifiche.html">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <header>
                <form action="sort_post.php" method="post" name="sort_form">
                    <label for="sort">Ordina per</label>
                    <select name="sort" id="sort" onchange="this.form.submit()">
                        <option value="" selected>Seleziona</option>
                        <option value="data">Data</option>
                        <option value="like">Like</option>
                        <option value="comm">Commenti</option>
                    </select>
                </form>
                <button id="add_post_button" class="access_required" onclick="mostraFormPost()">Aggiungi post</button>
                <form action="add_post.php" method="post" name="add_post_form">
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
            </header>
            <main>
                <ul>
                    <?php foreach($templateParams["post"] as $post): ?>
                    <li>
                        <table>
                            <tr>
                                <td><button onclick="accessProfile()"></button><?php echo $post["UserPost"]; ?></td>
                                <td><?php echo $post["DataPost"]; ?></td>
                                <td><button onclick="removePost($post['NrPost'], $post['UserPost'])" class="access_required">Rimuovi</button></td>
                            </tr>
                            <tr><td><?php echo $post["TestoPost"]; ?></td></tr>
                            <tr><td><img src="$post['ImmaginePost']" alt=""/></td></tr>
                            <tr>
                                <td><?php echo $post["LikePost"]; ?></td>
                                <td><button name="$post['NrPost']_like_button" class="preference_button" onclick="like($post['NrPost'])">Like</button></td>  <!-- aggiunge un like al post (se c'era già un dislike lo rimuove) e cambia il testo in "Liked" ed il colore in ciano; se c'è già un like si rimuove (toglie il like, torna come prima e non fa altro) -->
                                <td><?php echo $post["DislikePost"]; ?></td>
                                <td><button name="$post['NrPost']_dislike_button" class="preference_button" onclick="dislike($post['NrPost'])">Dislike</button></td>  <!-- aggiunge un dislike al post (se c'era già un like lo rimuove) e cambia il testo in "Disliked" ed il colore in magenta; se c'è già un dislike si rimuove -->
                                <td><button id="add_comment_button" class="access_required" onclick="mostraFormCommenti($post['NrPost'], $post['UserPost'], $post['DataPost'], '')">Commenta</button></td>
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
                                            <td><button onclick="removeComment($commento['NrCommento'], $commento['UserCommento'])" class="access_required">Rimuovi</button></td>
                                            <!-- removeComment non funziona se l'utente registrato non è l'autore del commento -->
                                        </tr>
                                        <tr><td><?php echo $commento["TestoCommento"]; ?></td></tr>
                                        <tr><td><img src="$commento['ImmagineCommento']" alt=""/></td></tr>
                                        <tr>
                                            <td><?php echo $commento["LikeCommento"]; ?></td>
                                            <td><button id="$commento['NrCommento']_like_button" class="preference_button" onclick="like($commento['NrCommento'])">Like</button></td>
                                            <td><?php echo $commento["DislikeCommento"]; ?></td>
                                            <td><button id="$commento['NrCommento']_dislike_button" class="preference_button" onclick="dislike($commento['NrCommento'])">Dislike</button></td>
                                            <td><button onclick="mostraFormCommenti($post['NrPost'], $post['UserPost'], $post['DataPost'], '@' + $commento['UserCommento'])">Rispondi</button></td>
                                        </tr>
                                    </table>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <form action="add_comment.php" method="post" name="add_comment_form">
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
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </main>
            </section>
        </main>
        <script src="js/generale.js" type="text/javascript"></script>
        <script src="js/index.js" type="text/javascript"></script>
    </body>
</html>
