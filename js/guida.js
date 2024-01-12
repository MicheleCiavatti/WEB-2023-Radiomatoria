function nascondiInvito() {
    if(!empty($_SESSION["nomeUtente"])) {   //if(isUserLoggedIn()) {
        document.getElementById('invito').hidden = true;
    }
}
