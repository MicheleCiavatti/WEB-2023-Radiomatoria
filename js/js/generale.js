function navBar() {
    if(!empty($_SESSION["nomeUtente"])) { //if(isUserLoggedIn()){
        document.getElementById('pag_creazione').hidden = true;
        document.getElementById('pag_accesso').hidden = true;
    } else {
        document.getElementById('pag_profilo').hidden = true;
        document.getElementById('pag_notifiche').hidden = true;
        document.getElementById('pag_uscita').hidden = true;
    }
}
