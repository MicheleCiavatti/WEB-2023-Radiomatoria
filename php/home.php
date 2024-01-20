<?php
session_start();
/*Check for user logged: if we want to allow a user not logged to access this page, this need to be changed*/
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
                <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                <li><a href="#">Home page</a></li>
                <li><a href="guide.php">Guida</a></li>
                <li><a href="includes/logout.inc.php">Logout</a></li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <!-- Vuoto in un template -->
        </main>
    </body>
</html>
