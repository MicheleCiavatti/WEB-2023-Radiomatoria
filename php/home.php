<?php
session_start();
require_once "includes/homepageInfo.inc.php";
$post_list = isset($_SESSION['NomeUtente']) ? getPosts($_SESSION['NomeUtente']) : getPosts(null);
if(isset($_SESSION['NomeUtente'])) {
    if(isset($_GET['relation'])) {
        $origin = $_GET['origin'];
        $sort = $_GET['sort'];
        $order = $_GET['order'];
    } else {
        $origin = "all";
        $sort = "data";
        $order = 1;
    }
}
if (isset($_SESSION['NomeUtente']) && empty(glob($_SESSION['FotoProfilo']))) {
    $_SESSION['FotoProfilo'] = '../img/default.png';
    resetPropic($_SESSION['NomeUtente']);
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
                    <li class="current_page"><a href="home.php">Home</a></li>
                    <li><a href="guida.php">Guida</a></li>
                    <li><a href="../signup.html">Signup</a></li>
                    <li><a href="../login.html">Login</a></li>
                </ul>
            </nav>
        <?php else: ?> <!-- If user is logged in -->
            <nav>
                <ul>
                    <li class="current_page"><a href="home.php">Home</a></li>
                    <li><a href="guida.php">Guida</a></li>
                    <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <li><a href="includes/logout.inc.php">Logout</a></li>
                    <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                </ul>
            </nav>
        <?php endif; ?>
        <main>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <header id="user_data">
                        <img src="<?= $_SESSION['FotoProfilo']; ?>" alt=""/>
                        <p><?= $_SESSION['NomeUtente']; ?></p>
                </header>
            <?php endif; ?>
            <section>
                <form action="selectPostHome.php" method="get" name="select_form_home" id="select_form">
                    <?php if(isset($_SESSION['NomeUtente'])): ?>
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
                <?php if(isset($_SESSION['NomeUtente'])): ?>
                    <button id="add_post_button" class="access_required">Aggiungi post</button>
                    <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data" id="add_post_form">
                        <input type="hidden" name="from_home"/>
                        <ul>
                            <li><label>Immagine post<input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></label></li>  
                            <li><label>Testo post<textarea name="post_text" placeholder="Scrivi un post" rows="2" cols="50" required></textarea></label></li>
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
                                    <td><button id="like_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" class="preference_button" name="like_button">Like</button></td>
                                </tr>
                                <tr>
                                    <td id="dislike_number_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0"><?= $post['DislikePost']; ?></td>
                                    <td><button id="dislike_button_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>_0" class="preference_button" name="dislike_button">Dislike</button></td>
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
                                <?php if (isset($_SESSION['NomeUtente'])): ?>
                                    <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data" class="add_comment_form" id="add_comment_<?= $post['NrPost']; ?>_<?= $post['Creatore']; ?>">
                                        <input type="hidden" name="post_author" value="<?= strval($post['Creatore']) ?>" />
                                        <input type="hidden" name="post_number" value="<?= strval($post['NrPost']) ?>" />
                                        <label>Immagine commento<input type="file" name="comment_image" accept=".jpg, .jpeg, .png"></label></br>
                                        <label>Testo commento<textarea name="comment_text" rows="2" cols="50" required></textarea></label>
                                        <input type="hidden" name="from_home"/>
                                        <input type="reset" class="comment_reset" value="Annulla commento" />
                                        <input type="submit" value="Pubblica" />
                                    </form>
                                <?php endif; ?>
                            </header>
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
