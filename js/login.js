function mostraIndizio() {
    let indizio = getClue(document.getElementById('address'));
    document.getElementById('clue').innerHTML = indizio;
}

function login_result() {
    let login_form = document.getElementByName("login_form");

    login_form.addEventListener("submit", (event) => {
        event.preventDefault();
        fetch(login_form.action, {
            method: 'POST',
            body: new URLSearchParams(new FormData(login_form))
        }).then((response) => response.text())
        .then((response) => {
            if(isUserLoggedIn()) {
                accessProfile(response);
            } else {
                document.getElementById("login_fail").innerHTML = response;
            }
        })
        .catch(err => document.getElementById("login_fail").innerHTML = err)
    })
    
}
