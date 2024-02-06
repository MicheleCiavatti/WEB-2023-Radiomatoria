document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("fetch_clue").addEventListener('click', function() { mostraIndizio(); });
});

const error = new URLSearchParams(window.location.search).get('error');
if (error) {
    let main = document.querySelector('main');
    switch (error) {
        case 'usernotfound': main.innerHTML += `<p class="error">Mail non registrata</p>`; break;
        case 'wrongpassword': main.innerHTML += `<p class="error">Password errata</p>`; break;
        default: main.innerHTML += `<p class="error">Errore sconosciuto</p>`; break;
    }
}

const mail_input = document.getElementById('address');
const clue_output = document.getElementById('clue');

function mostraIndizio() {
    let mail = mail_input.value;
    let xhr = new XMLHttpRequest();
    let url = 'php/functions/getClue.php?mail=' + encodeURIComponent(mail);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Indizio acquisito con successo dal server');
            clue_output.innerHTML = xhr.responseText;
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'acquisizione dell'indizio:", xhr.status);
        }
    };
    xhr.send();
}
