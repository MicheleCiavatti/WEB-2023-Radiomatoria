function accessProfile(username) {
    window.location.href = "profile.php";
    document.getElementsByTagName("body").onload("<?php $data = profileAccess('" + username + "'); $utente = $data[0]; $frequenze = $data[1]; $orari = $data[2]; $amici = $data[3]; $seguiti = $data[4]; $bloccati = $data[5]; ?>");
}