<?php
session_start();
require_once "includes/homepageInfo.inc.php";
$posts = isset($_SESSION['NomeUtente']) ? getPosts($_SESSION['NomeUtente']) : getPosts(null);
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
            <h1>Long Light</h1>
        </header>
        <aside>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <img src="<?= $_SESSION['FotoProfilo']; ?>" alt=""/>
                <p><?= $_SESSION['NomeUtente']; ?></p>
            <?php endif; ?>
        </aside>
        <nav>
            <ul>
                <?php if (isset($_SESSION['NomeUtente'])): ?>
                    <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <li><a href="includes/logout.inc.php">Logout</a></li>
                    <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                <?php else: ?>
                    <li><a href="../signup.html">Crea Account</a></li>
                    <li><a href="../login.html">Login</a></li>
                <?php endif; ?>
                <li><a href="#">Home page</a></li>
                <li><a href="guida.php">Guida</a></li>
            </ul>
        </nav>
        <main>
            <?php if (isset($_SESSION['NomeUtente'])): ?>
                <section>
                    <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                        <ul>
                            <li><input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></li>  
                            <li><textarea name="post_text" placeholder="Scrivi un post" required></textarea></li>
                            <li><button type="submit" name="upload_post">Pubblica</button></li>
                        </ul>
                    </form>
                </section>
            <?php endif; ?>
            <?php foreach ($posts as $post): ?>
                <article>
                    <header>
                        <h2><a href="profile.php?id=<?=strval($post['Creatore'])?>"><?= strval($post['Creatore']); ?></a></h2>
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
                            <form action="includes/addComment.inc.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="comment_image" accept=".jpg, .jpeg, .png">
                                <textarea name="comment_text" rows="1" cols="100" placeholder="Rispondi al post di <?= strval($post['Creatore']) ?>" required></textarea>
                                <input type="hidden" name="post_author" value="<?= strval($post['Creatore']) ?>">
                                <input type="hidden" name="post_number" value="<?= strval($post['NrPost']) ?>">
                                <input type="submit" value="Pubblica">
                            </form>
                        </section>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>      
        </main>
    </body>
</html>
