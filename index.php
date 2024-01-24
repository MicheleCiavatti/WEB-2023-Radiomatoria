<?php
    require_once './php/generale.php';
    require_once './php/selectPostHome.php';
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Home page</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body onload="hide()">
        <header>
            <h1>Long Light</h1>
        </header>
        <aside>
            <?php 
                session_start();
                if(isset($_COOKIE['NomeUtente'])):
            ?>
                <img id="FotoProfilo" src="<?= $_COOKIE['FotoProfilo'] ?>" alt=""/>
                <a id="NomeUtente" href="profile.php?id=<?= $_COOKIE['NomeUtente']; ?>"><?= $_COOKIE['NomeUtente']; ?></a>
            <?php endif; ?>
        </aside>
        <nav>
            <ul>
                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                    <li id="pag_profilo"><a href="profile.php?id=<?= $_COOKIE['NomeUtente']; ?>">Profilo</a></li>
                <?php endif; ?>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <?php if(isset($_COOKIE['NomeUtente'])): ?>
                    <li id="pag_notifiche"><a href="notifiche.php">Notifiche</a></li>
                    <li id="pag_uscita"><a href="includes/logout.inc.php">Logout</a></li>
                <?php else: ?>
                    <li id="pag_creazione"><a href="signup.php">Crea account</a></li>
                    <li id="pag_accesso"><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <header>
                <section>
                    <form action="selectPostHome.php" method="post" name="select_form_home" id="select_form">
                        <?php if(isset($_COOKIE['NomeUtente'])): ?>
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
                    <?php if(isset($_COOKIE['NomeUtente'])): ?>
                        <button id="add_post_button" class="access_required" onclick="mostraFormPost()">Aggiungi post</button>
                        <form action="addPost.php" method="post" name="add_post_form">
                            <table>
                                <tr>
                                    <td><label for="post_img">Inserisci immagine (opzionale)</label></td>
                                    <td><input type="image" name="post_img" id="post_img" alt=""/></td>
                                </tr>
                                <tr>
                                    <td><label for="post_text">Inserisci testo</label></td>
                                    <td><textarea name="post_text" id="post_text" required></textarea></td>
                                </tr>
                                <tr>
                                    <td><input type="reset" value="Annulla"/></td>
                                    <td><input type="submit" value="Scrivi"/></td>
                                </tr>
                            </table>
                        </form>
                    <?php endif; ?>
                </section>
            </header>
            <article>
                <ul id="post_list">
                </ul>
            </article>
        </main>
        <script src="js/index.js" type="text/javascript"></script>
    </body>
</html>
