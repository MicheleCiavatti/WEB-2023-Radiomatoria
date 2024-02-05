<?php
    session_start();
    require_once "includes/homepageInfo.inc.php";
    $posts = isset($_SESSION['NomeUtente']) ? getPosts($_SESSION['NomeUtente']) : getPosts(null);
        if (isset($_SESSION['NomeUtente']) && empty(glob($_SESSION['FotoProfilo']))) {
            $_SESSION['FotoProfilo'] = '../img/default.png';
            resetPropic($_SESSION['NomeUtente']);
        }
    $n_notifications = isset($_SESSION['NomeUtente']) ? getNotifications($_SESSION['NomeUtente']) : null;
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
                    <?php if ($n_notifications == 0): ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                    <?php else: ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche<sup>(<?= $n_notifications; ?>)</sup></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <main>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <header>
                        <img src="<?= $_SESSION['FotoProfilo']; ?>" alt=""/>
                        <p><?= $_SESSION['NomeUtente']; ?></p>
                </header>
            <?php endif; ?>
            <!-- post form -->
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <section>
                    <p id="session_user_name"><?= $_SESSION['NomeUtente']; ?></p>
                    <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                        <ul>
                            <li><label>Carica immagine<input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></label></li>  
                            <li><label><textarea name="post_text" placeholder="Scrivi un post" required></textarea></label></li>
                            <li><button type="submit" class="pos" name="upload_post">Pubblica</button></li>
                        </ul>
                    </form>
                </section>
            <?php endif; ?>
            <!-- posts -->
            <?php foreach ($posts as $post): ?>
                <article>
                    <header>
                        <p><a href="profile.php?id=<?=strval($post['Creatore'])?>"><?= strval($post['Creatore']); ?></a></p>
                        <p><?= strval($post['DataPost']); ?></p>
                        <?php if ($post['ImmaginePost'] != null): ?>
                            <img src="<?= strval($post['ImmaginePost']); ?>" alt=""/>
                        <?php endif; ?>
                        <section>
                            <p><?= strval($post['TestoPost']); ?></p>
                            <!-- like/un-like button -->
                            <ul>
                                <li>Likes: <?= getLikes($post['Creatore'], $post['NrPost']) ?></li>
                                <?php if (isset($_SESSION['NomeUtente'])): ?>
                                    <li>
                                        <input type="hidden" value="<?= strval($post['Creatore']); ?>">
                                            <?php if (!isLiked($_SESSION['NomeUtente'], $post['Creatore'], $post['NrPost'])): ?>
                                                <button class="like_button pos">Like</button>
                                            <?php else: ?>
                                                <button class="unlike_button neg">Un-like</button>
                                            <?php endif; ?>
                                        <input type="hidden" value="<?= strval($post['NrPost']); ?>">
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </section>
                    </header>
                    <!-- comments -->
                    <?php $comments = getComments($post['Creatore'], $post['NrPost']);?>
                    <section>
                        <?php if (isset($_SESSION['NomeUtente'])): ?>
                            <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data">
                                <ul>
                                <li><label>Carica immagine per commento<input type="file" name="comment_image" accept=".jpg, .jpeg, .png"></label></li>
                                <li><label><textarea name="comment_text" rows="1" cols="100" placeholder="Rispondi al post di <?= strval($post['Creatore']) ?>" required></textarea></label></li>
                                <li><input type="hidden" name="post_author" value="<?= strval($post['Creatore']) ?>"></li>
                                <li><input type="hidden" name="post_number" value="<?= strval($post['NrPost']) ?>"></li>
                                <li><input type="hidden" name="from_home"></li>
                                <li><input class="pos" type="submit" value="Pubblica"></li>
                                </ul>
                            </form>
                        <?php endif; ?>
                        <ul>
                            <?php foreach ($comments as $comment): ?>
                                <li>
                                    <p><a href="profile.php?id=<?=strval($comment['AutoreCommento']);?>"><?=strval($comment['AutoreCommento'])?></a> <?= strval($comment['DataCommento']);?></p>
                                    <p><?= strval($comment['TestoCommento']); ?></p>
                                    <?php if ($comment['ImmagineCommento'] != null): ?>
                                        <img src="<?= strval($comment['ImmagineCommento']); ?>" alt=""/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                </article>
            <?php endforeach; ?>      
        </main>
        <script src="../js/home.js" ></script>
    </body>
</html>
