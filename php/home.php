<?php
session_start();
if (!(isset($_SESSION['NomeUtente']) && isset($_SESSION['FotoProfilo'])))
    header('location: ../../login.html?error=needtologin');
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
                <li><a href="../profile.php">Profilo</a></li>
                <li><a href="#">Home page</a></li>
                <li><a href="guide.html">Guida</a></li>
                <li><a href="includes/logout.inc.php">Logout</a></li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <!-- Vuoto in un template -->
        </main>
    </body>
</html>
