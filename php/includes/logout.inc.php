<?php

$time = time() - 3600; //1 hour ago
setcookie('NomeUtente', "", $time, "/");
setcookie('FotoProfilo', "", $time, "/");
setcookie('Indirizzo', "", $time, "/");
setcookie('Città', "", $time, "/");
setcookie('DataNascita', "", $time, "/");
setcookie('IndirizzoMail', "", $time, "/");
setcookie('Indizio', "", $time, "/");
session_start();
session_unset();
session_destroy();

header('location: ../../login.html');