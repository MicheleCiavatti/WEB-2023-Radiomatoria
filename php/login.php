<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Login & Signup</title>
        <meta charset="UTF-8"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>Long Light</h1>
        </header>
        <nav>
            <ul>
                <li><a href="#">Profilo</a></li>
                <li><a href="index.html">Home page</a></li>
                <li><a href="guide.html">Guida</a></li>
                <li><a href="create.html">Crea account</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="#">Notifiche</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <h2>SIGNUP</h2>
                <form action="includes/signup.inc.php" method="post">
                    <ul>
                        <li><input name="uid" placeholder="Username" required></li>
                        <li><input name="address" placeholder="Address" required></li>
                        <li><input name="city" placeholder="City" required></li>
                        <li><input type="email" name="mail" placeholder="Email" required></li>
                        <li><input type="date" name="birthdate" placeholder="Birthdate" required></li>
                        <li><input type="password" name="pw" placeholder="Password" required></li>
                        <li><input type="password" name="pwrepeat" placeholder="Repeat Password" required></li>
                        <li><input name="clue" placeholder="Password clue" required></li>
                        <li><button type="submit" name="submit">SIGN UP</button></li>
                    </ul>
                </form>
            </section>
            <section>
                <h2>LOGIN</h2>
                <form action="includes/login.inc.php" method="post">
                    <ul>
                        <li><input name="uid" placeholder="Username" required></li>
                        <li><input type="password" name="pw" placeholder="Password" required></li>
                        <li><button type="submit" name="submit">LOGIN</button></li>
                    </ul>
                </form>
            </section>
        </main>
    </body>