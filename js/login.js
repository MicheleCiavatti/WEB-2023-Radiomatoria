const error = new URLSearchParams(window.location.search).get('error');
if (error) {
    let main = document.querySelector('main');
    switch (error) {
        case 'usernotfound': main.innerHTML += `<p class="error">Mail non registrata</p>`; break;
        case 'wrongpassword': main.innerHTML += `<p class="error">Password errata</p>`; break;
        default: main.innerHTML += `<p class="error">Errore sconosciuto</p>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const clueButton = document.getElementById('clue_button');
    clueButton.addEventListener('click', () => {
        const mail = document.getElementById('address').value;
        if (mail === '' || !mail.includes('@')) {
            document.getElementById('clue').innerHTML = 'Inserisci una mail';
        } else {
            getClue(mail);
        }
    });
});

function getClue(mail) {
    const xhr = new XMLHttpRequest();
    const url = 'php/functions/getClue.php?mail=' + encodeURIComponent(mail);
    xhr.open('GET', url, false);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const clue = xhr.responseURL.split('=')[1];
            document.getElementById('clue').innerHTML = clue;
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante il recupero del clue:', xhr.status);
        }
    };
    xhr.send();
}