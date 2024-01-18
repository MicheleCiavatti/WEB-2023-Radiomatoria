<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Login</title>
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
                <form action="php/includes/login.inc.php" method="post" name="login_form">
                    <table>
                        <tr>
                            <td><label for="address">Indirizzo e-mail</label></td>
                            <td><input type="email" name="address" id="address" required/></td>
                        </tr>
                        <tr>
                            <td><label for="pw">Password</label></td>
                            <td><input type="password" name="pw" id="pw" required/></td>
                        </tr>
                        <tr>
                            <td><button type="button" onclick="mostraIndizio()">Mostra indizio</button></td>
                            <td><span id="clue"></span></td>
                        </tr>
                        <tr>
                            <td><input type="reset" value="Annulla" title="Annulla"/></td>
                            <td><input type="submit" name="submit" value="Login"/></td>
                        </tr>
                    </table>
                </form>
                <span id="login_fail"></span>
            </section>
            <footer>Non hai un account?<a href="signup.html">Signup</a></footer>
        </main>
        <script src="js/login.js" type="text/javascript"></script>
    </body>
</html>
