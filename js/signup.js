const error = new URLSearchParams(window.location.search).get('error');
if (error) {
    let main = document.querySelector('main');
    switch (error) {
        case 'invaliduid': main.innerHTML += `<p class="error">Username non valido</p>`; break;
        case 'invalidmail': main.innerHTML += `<p class="error">Mail non valida</p>`; break;
        case 'passwordnotmatch': main.innerHTML += `<p class="error">Le password non coincidono</p>`; break;
        case 'userormailtaken': main.innerHTML += `<p class="error">Username o mail già in uso</p>`; break;
        default: main.innerHTML += `<p class="error">Errore sconosciuto</p>`;
    }
}