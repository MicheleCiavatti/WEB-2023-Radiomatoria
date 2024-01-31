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
        <img src="<?php echo $_SESSION['FotoProfilo']; ?>" alt=""/>
        <p><?php echo $_SESSION['NomeUtente']; ?></p>
        </aside>
        <nav>
            <ul>
                <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                <li><a href="#">Home page</a></li>
                <li><a href="guida.php">Guida</a></li>
                <li><a href="includes/logout.inc.php">Logout</a></li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <form action="includes/addPost.inc.php" method="post" enctype="multipart/form-data">
                <ul>
                    <li><input type="file" name="post_image" accept=".jpg, .jpeg, .png"/></li>  
                    <li><textarea name="post_text" placeholder="Scrivi un post" required></textarea></li>
                    <li><button type="submit" name="upload_post">Pubblica</button></li>
                </ul>
            </section>
            <?php foreach ($posts as $post): ?>
                <article>
                    <header>
                        <h2><?= strval($post['Creatore']); ?></h2>
                        <p><?= strval($post['DataPost']); ?></p>
                        <?php if ($post['ImmaginePost'] != null): ?>
                            <img src="<?= strval($post['ImmaginePost']); ?>" alt=""/>
                        <?php endif; ?>
                    </header>
                    <section>
                        <p><?= strval($post['TestoPost']); ?></p>
                    </section>
                </article>
            <?php endforeach; ?>      
        </main>
    </body>
</html>
