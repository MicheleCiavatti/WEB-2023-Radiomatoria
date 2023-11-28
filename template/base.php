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
        <nav>
            <ul>
                <li><a href="#">Profilo</a></li>
                <li><a href="index.html">Home page</a></li>
                <li><a href="guide.html">Guida</a></li>
                <li><a href="create.html">Crea account</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="#">Notifiche</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <header>
                    <form action="sort_post.php" method="post" name="sort_form">
                        <label for="sort">Ordina per</label>
                        <select name="sort" id="sort">  <!-- Auto-submit quando un'opzione viene selezionata -->
                            <option value="data">Data</option>
                            <option value="like">Like</option>
                            <option value="comm">Commenti</option>
                        </select>
                    </form>
                    <button>Aggiungi post</button>  <!-- Rende add_post_form visibile se è nascosto e lo nasconde di nuovo altrimenti -->
                    <form action="add_post.php" method="post" name="add_post_form">  <!-- Nascosto -->
                        <table>
                            <tr>
                                <td><label for="post_img">Inserisci immagine (opzionale)</label></td>
                                <td><input type="image" name="post_img" id="post_img"/></td>
                            </tr>
                            <tr>
                                <td><label for="post_text">Inserisci testo</label></td>
                                <td><textarea name="post_text" id="post_text" required></textarea></td>
                            </tr>
                            <tr>
                                <td><input type="reset" value="Annulla"/></td>  <!-- Resetta i campi e nasconde di nuovo il form -->
                                <td><input type="submit" value="Scrivi"/></td>  <!-- Resetta i campi, nasconde di nuovo il form e crea un nuovo post -->
                            </tr>
                        </table>
                    </form>
                </header>
                <main>
                    <ul>
                        <?php foreach($templateParams["post"] as $post): ?> <!-- Di default sono ordinati per data -->
                        <li>
                            <table>
                                <tr>
                                    <td><?php echo $post["UserPost"]; ?></td>
                                    <td><?php echo $post["DataPost"]; ?></td>
                                    <td><button>Rimuovi</button></td>
                                </tr>
                                <tr><td><?php echo $post["TestoPost"]; ?></td></tr>
                                <tr><td><?php echo $post["ImmaginePost"]; ?></td></tr>
                                <tr>
                                    <td><?php echo $post["LikePost"]; ?></td>
                                    <td><button>Like</button></td>  <!-- aggiunge un like al post (se c'era già un dislike lo rimuove) e cambia il testo in "Liked" ed il colore in ciano; se c'è già un like si rimuove (toglie il like, torna come prima e non fa altro) -->
                                    <td><?php echo $post["DislikePost"]; ?></td>
                                    <td><button>Dislike</button></td>  <!-- aggiunge un dislike al post (se c'era già un like lo rimuove) e cambia il testo in "Disliked" ed il colore in magenta; se c'è già un dislike si rimuove -->
                                    <td><button>Commenta</button></td>  <!-- Rende add_comment_form visibile se è nascosto e lo nasconde di nuovo altrimenti -->
                                </tr>
                                <tr><td><button>Mostra commenti</button></td></tr>
                            </table>
                            <ul>
                                <?php foreach($templateparams["commenti"] as $commento): ?> <!-- Non viene eseguito automaticamente: è attivato da Mostra commenti -->
                                <li>
                                    <table>
                                        <tr>
                                            <td><?php echo $commento["UserCommento"]; ?></td>
                                            <td><?php echo $commento["DataCommento"]; ?></td>
                                            <td><button>Rimuovi</button></td>
                                        </tr>
                                        <tr><td><?php echo $commento["TestoCommento"]; ?></td></tr>
                                        <tr><td><?php echo $commento["ImmagineCommento"]; ?></td></tr>
                                        <tr>
                                            <td><?php echo $commento["LikeCommento"]; ?></td>
                                            <td><button>Like</button></td>  <!-- aggiunge un like al post (se c'era già un dislike lo rimuove) e cambia il testo in "Liked" ed il colore in ciano; se c'è già un like si rimuove -->
                                            <td><?php echo $commento["DislikeCommento"]; ?></td>
                                            <td><button>Dislike</button></td>  <!-- aggiunge un dislike al post (se c'era già un like lo rimuove) e cambia il testo in "Disliked" ed il colore in magenta; se c'è già un dislike si rimuove -->
                                            <td><button>Rispondi</button></td>  <!-- Mostra add_comment_form se è nascosto (e lo nasconde di nuovo altrimenti) ed inserisce nella textarea @username dell'autore di questo commento -->
                                        </tr>
                                    </table>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <form action="add_comment.php" method="post" name="add_comment_form">  <!-- Nascosto -->
                                <table>
                                    <tr>
                                        <td><label for="comment_img">Inserisci immagine (opzionale)</label></td>
                                        <td><input type="image" name="comment_img" id="comment_img"/></td>
                                    </tr>
                                    <tr>
                                        <td><label for="comment_text">Inserisci testo</label></td>
                                        <td><textarea name="comment_text" id="comment_text" required></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><input type="reset" value="Annulla"/></td>  <!-- Resetta i campi e nasconde di nuovo il form -->
                                        <td><input type="submit" value="Scrivi"/></td>  <!-- Resetta i campi, nasconde di nuovo il form e crea un nuovo post -->
                                    </tr>
                                </table>
                            </form>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </main>
            </section>
        </main>
    </body>
</html>
