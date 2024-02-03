const error = new URLSearchParams(window.location.search).get('error');
console.log(error);
if (error) {
    let main = document.querySelector('main');
    switch (error) {
        case 'usernotfound': main.innerHTML += `<p class="error">Mail non registrata</p>`; break;
        case 'wrongpassword': main.innerHTML += `<p class="error">Password errata</p>`; break;
        default: main.innerHTML += `<p class="error">Errore sconosciuto</p>`;
    }
}

function mostraIndizio() {
    let indizio = getClue(document.getElementById('address'));
    document.getElementById('clue').innerHTML = indizio;
}

