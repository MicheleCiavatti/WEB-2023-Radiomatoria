<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Home</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1><!-- Titolo temporaneo del sito -->
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
                <li><a href="guide.html">Guida</a></li>
                <li><a href="create.html">Crea account</a></li>
                <li>
                    <?php 
                        if (isset($_SESSION['uid'])):
                    ?>
                    <a href="includes/logout.inc.php">Logout</a>
                    <?php else: ?>
                    <a href="login.html">Login</a>
                    <?php endif; ?>
                </li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <!-- Vuoto in un template -->
        </main>
    </body>
</html>
