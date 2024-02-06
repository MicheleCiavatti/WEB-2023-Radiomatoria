<?php
session_start();
require_once "includes/homepageInfo.inc.php";
if (isset($_SESSION['NomeUtente'])) {
    $post_list = getPosts($_SESSION['NomeUtente']);
    $n_notifications = getNotifications($_SESSION['NomeUtente']);
    if(empty(glob($_SESSION['FotoProfilo']))) {
        $_SESSION['FotoProfilo'] = '../img/default.png';
        resetPropic($_SESSION['NomeUtente']);
    }
} else {
    $post_list = getPosts(null);
    $n_notifications = 0;
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Home</title>
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
                    <li class="current_page" id="pag_principale"><a href="home.php">Home</a></li>
                    <li id="pag_guida"><a href="guida.php">Guida</a></li>
                    <li id="pag_creazione"><a href="../signup.html">Signup</a></li>
                    <li id="pag_accesso"><a href="../login.html">Login</a></li>
                </ul>
            </nav>
        <?php else: ?> <!-- If user is logged in -->
            <nav>
                <ul>
                    <li class="current_page" id="pag_principale"><a href="home.php">Home</a></li>
                    <li id="pag_guida"><a href="guida.php">Guida</a></li>
                    <li id="pag_profilo"><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
                    <?php if ($n_notifications == 0): ?>
                        <li id="pag_notifiche"><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                    <?php else: ?>
                        <li id="pag_notifiche"><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche<sup>(<?= $n_notifications; ?>)</sup></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <main>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <header id="user_data">
                        <img src="<?= $_SESSION['FotoProfilo']; ?>" alt=""/>
                        <p id="session_user_name"><?= $_SESSION['NomeUtente']; ?></p>
                </header>
            <?php endif; ?>
            <section>
                <!-- add post form -->
                <?php if(isset($_SESSION['NomeUtente'])): ?>
                    <button id="add_post_button" class="access_required">Aggiungi post</button>
                    <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data" id="add_post_form">
                        <input type="hidden" name="from_home"/>
                        <ul>
                            <li><label for="post_image">Carica immagine<input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></label></li>  
                            <li><label for="post_text">Testo post<textarea name="post_text" placeholder="Scrivi un post" rows="2" cols="50" required></textarea></label></li>
                            <li><button type="submit" name="upload_post">Pubblica</button></li>
                        </ul>
                    </form>
                <?php endif; ?>
            </section>
            <section id="post_column">
                <?php foreach ($post_list as $post): ?>
                    <article class="post" id="post<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">
                        <header>
                            <p><a href="profile.php?id=<?=strval($post['Creatore'])?>"><?= strval($post['Creatore']); ?></a></p>
                            <p><?= strval($post['DataPost']); ?></p>
                            <?php if ($post['ImmaginePost'] != null): ?>
                                <img src="<?= strval($post['ImmaginePost']); ?>" alt=""/>
                            <?php endif; ?>
                        </header>
                        <section>
                            <p><?= strval($post['TestoPost']); ?></p>
                            <table>
                                <tr>
                                    <td id="like_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0"><?= $post['LikePost']; ?></td>
                                    <td><button id="like_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" <?php if(isset($_SESSION['NomeUtente'])): echo 'name="like_button"';
                                    if (isLiked($_SESSION['NomeUtente'], $post['Creatore'], $post['NrPost'], null)): echo 'class="preferred_button"'; else: echo 'class="preference_button"'; endif; endif;?>>Like</button></td>
                                </tr>
                                <tr>
                                    <td id="dislike_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0"><?= $post['DislikePost']; ?></td>
                                    <td><button id="dislike_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" <?php if(isset($_SESSION['NomeUtente'])): echo 'name="dislike_button"';
                                    if (isDisliked($_SESSION['NomeUtente'], $post['Creatore'], $post['NrPost'], null)): echo 'class="preferred_button"'; else: echo 'class="preference_button"'; endif; endif;?>>Dislike</button></td>
                                </tr>
                            </table>
                            <?php if(isset($_SESSION['NomeUtente'])): ?>
                                <button class="access_required" name="comment_post" id="comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">Commenta</button>
                                <?php if($post['Creatore'] == $_SESSION['NomeUtente']): ?>
                                    <button class="access_required" name="remove_post" id="remove_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">Rimuovi</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </section>
                        <section>
                            <header>
                                <!-- add comment -->
                                <?php if (isset($_SESSION['NomeUtente'])): ?>
                                    <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data" class="add_comment_form" id="add_comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">
                                        <input type="hidden" name="post_author" value="<?= strval($post['Creatore']) ?>" />
                                        <input type="hidden" name="post_number" value="<?= strval($post['NrPost']) ?>" />
                                        <label for="comment_image">Carica immagine<input type="file" name="comment_image" accept=".jpg, .jpeg, .png"></label></br>
                                        <label for="comment_text">Testo commento<textarea name="comment_text" rows="2" cols="50" required></textarea></label>
                                        <input type="hidden" name="from_home"/>
                                        <input type="reset" class="comment_reset" value="Annulla commento" />
                                        <input type="submit" value="Pubblica" />
                                    </form>
                                <?php endif; ?>
                            </header>
                        <!-- comments -->
                        <?php
                                $comments = getComments($post['Creatore'], $post['NrPost']);
                                if(!empty($comments)):
                            ?>
                                <button id="show_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>" name="show_comments">Mostra commenti</button>
                                <ul id="<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_comment_list" name="comment_list">
                                    <?php foreach ($comments as $comment): ?>
                                        <li>
                                            <p><a href="profile.php?id=<?=strval($comment['AutoreCommento']);?>"><?=strval($comment['AutoreCommento'])?></a> <?= strval($comment['DataCommento']);?></p>
                                            <p><?= strval($comment['TestoCommento']); ?></p>
                                            <?php if ($comment['ImmagineCommento'] != null): ?>
                                                <img src="<?= strval($comment['ImmagineCommento']); ?>" alt=""/>
                                            <?php endif; ?>
                                            <table>
                                                <tr>
                                                    <td id="like_number_<?= $comment['NrPost']; ?>_<?= $comment['Creatore']; ?>_<?= $comment['NrCommento']; ?>"><?= $comment['LikeCommento']; ?></td>
                                                    <td><button id="like_button_<?= $comment['NrPost']; ?>_<?= $comment['Creatore']; ?>_<?= $comment['NrCommento']; ?>" name="like_button" 
                                                    <?php if (isLiked($_SESSION['NomeUtente'], $comment['Creatore'], $comment['NrPost'], $comment['NrCommento'])): echo 'class="preferred_button"'; else: echo 'class="preference_button"'; endif;?>>Like</button></td>
                                                </tr>
                                                <tr>
                                                    <td id="dislike_number_<?= $comment['NrPost']; ?>_<?= $comment['Creatore']; ?>_<?= $comment['NrCommento']; ?>"><?= $comment['DislikeCommento']; ?></td>
                                                    <td><button id="dislike_button_<?= $comment['NrPost']; ?>_<?= $comment['Creatore']; ?>_<?= $comment['NrCommento']; ?>" name="dislike_button"
                                                    <?php if (isDisliked($_SESSION['NomeUtente'], $comment['Creatore'], $comment['NrPost'], $comment['NrCommento'])): echo 'class="preferred_button"'; else: echo 'class="preference_button"'; endif;?>>Dislike</button></td>
                                                </tr>
                                            </table>
                                            <footer>
                                                <?php if(isset($_SESSION['NomeUtente'])): ?>
                                                    <?php if($comment['AutoreCommento'] != $_SESSION['NomeUtente']): ?>
                                                        <button class="access_required" name="answer_comment" id="comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>-<?= $comment['NrCommento']; ?>">Rispondi a <?= $comment['AutoreCommento']; ?></button>
                                                    <?php else: ?>
                                                        <button class="access_required" name="remove_comment" id="remove_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_<?= $comment['NrCommento']; ?>">Rimuovi</button>
                                                    <?php endif;
                                                    endif; ?>
                                            </footer>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </section>
                    </article>
                <?php endforeach; ?>     
            </section> 
        </main>
        <script src="../js/home.js" type="text/javascript"></script>
    </body>
</html>
