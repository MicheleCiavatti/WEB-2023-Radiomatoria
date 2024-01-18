<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Crea account</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <li id="pag_principale"><a href="index.php">Home page</a></li>
                <li id="pag_guida"><a href="guida.php">Guida</a></li>
                <li id="pag_creazione"><a href="signup.php">Crea account</a></li>
                <li id="pag_accesso"><a href="login.php">Login</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <form action="php/includes/signup.inc.php" method="post" name="new_account_form">
                    <table>
                        <tr>
                            <td><label for="address">Indirizzo e-mail</label></td>
                            <td><input type="email" name="address" id="address" required/></td>
                        </tr>
                        <tr>
                            <td><label for="city">Comune</label></td>
                            <td><input name="city" id="city" required/></td>
                        </tr>
                        <tr>
                            <td><label for="birthdate">Data di Nascita</label></td>
                            <td><input type="date" name="birthdate" id="birthdate" required/></td>
                        </tr>
                        <tr>
                            <td><label for="pw">Password</label></td>
                            <td><input type="password" name="pw" id="pw" minlength="8" required/></td>
                        </tr>
                        <tr>
                            <td><label for="pwrepeat">Password (conferma)</label></td>
                            <td><input type="password" name="pwrepeat" id="passwd2" minlength="8" required/></td>
                        </tr>
                        <tr>
                            <td><label for="clue">Indizio</label></td>
                            <td><textarea rows="2" cols="24" name="clue" id="clue" required></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="uid">Nome Utente</label></td>
                            <td><input type="text" name="uid" id="uid" required/></td>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Annulla" title="Annulla"/></td>
                            <td><input type="submit" name="submit" value="Crea account"/></td>
                        </tr>
                    </table>
                </form>
                <span id="create_fail"></span>
            </section>
            <section>
                <p>Indirizzo e-mail e Nome utente sono ambedue univoci: per la creazione di un nuovo account verranno accettati solamente valori assenti sul sito.<br/>L'Indizio consiste in un promemoria utilizzabile in fase di login per ricordare la propria password, inconsultabile senza aver inserito il relativo indirizzo e-mail.<p>
            </section>
            <footer>Hai un altro account?<a href="login.html">Login</a></footer>
        </main>
        <script src="js/create.js" type="text/javascript"></script>
    </body>
</html>
