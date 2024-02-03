<?php
session_start();
require_once "includes/homepageInfo.inc.php";
$posts = isset($_SESSION['NomeUtente']) ? getPosts($_SESSION['NomeUtente']) : getPosts(null);
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
        <?php if (isset($_SESSION['NomeUtente'])): ?>
            <aside>
                    <img src="<?= $_SESSION['FotoProfilo']; ?>" alt=""/>
                    <p><?= $_SESSION['NomeUtente']; ?></p>
            </aside>
        <?php endif; ?>
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
                <section>
                    <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                        <ul>
                            <li><label>Immagine post<input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></label></li>  
                            <li><label>Testo post<textarea name="post_text" placeholder="Scrivi un post" required></textarea></label></li>
                            <li><button type="submit" name="upload_post">Pubblica</button></li>
                        </ul>
                    </form>
                </section>
            <?php endif; ?>
            <?php foreach ($posts as $post): ?>
                <article>
                    <header>
                        <?php if ($post['ImmaginePost'] != null): ?>
                            <img src="<?= strval($post['ImmaginePost']); ?>" alt=""/>
                        <?php endif; ?>
                        <p><a href="profile.php?id=<?=strval($post['Creatore'])?>"><?= strval($post['Creatore']); ?></a></p>
                        <p><?= strval($post['DataPost']); ?></p>
                        <?php if ($post['ImmaginePost'] != null): ?>
                            <img src="<?= strval($post['ImmaginePost']); ?>" alt=""/>
                        <?php endif; ?>
                    </header>
                    <section><?= strval($post['TestoPost']); ?></section>
                    <?php $comments = getComments($post['Creatore'], $post['NrPost']); if (!empty($comments)): ?>
                        <section>
                            <ul>
                                <?php foreach ($comments as $comment): ?>
                                    <li>
                                        <?php if ($comment['ImmagineCommento'] != null): ?>
                                            <img src="<?= strval($comment['ImmagineCommento']); ?>" alt=""/>
                                        <?php endif; ?>
                                        <p><strong><a href="profile.php?id=<?=strval($comment['AutoreCommento']);?>"><?=strval($comment['AutoreCommento'])?></a></strong> <?= strval($comment['DataCommento']);?></p>
                                        <p><?= strval($comment['TestoCommento']); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (isset($_SESSION['NomeUtente'])): ?>
                                <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data">
                                    <label>Immagine commento<input type="file" name="comment_image" accept=".jpg, .jpeg, .png"></label>
                                    <label>Testo commento<textarea name="comment_text" rows="1" cols="100" placeholder="Rispondi al post di <?= strval($post['Creatore']) ?>" required></textarea></label>
                                    <input type="hidden" name="post_author" value="<?= strval($post['Creatore']) ?>">
                                    <input type="hidden" name="post_number" value="<?= strval($post['NrPost']) ?>">
                                    <input type="submit" value="Pubblica">
                                </form>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>      
        </main>
    </body>
</html>
