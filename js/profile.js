function removeFreq(f_to_remove) {
    let elements = document.getElementsByName('f' + f_to_remove.toString().replace('.','_'));
    elements.forEach((element) => {
        element.parentNode.removeChild(element);
    });
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/removeFrequency.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Frequenza rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della frequenza:', xhr.status);
        }
    };
    let data = encodeURI('f_to_remove=' + encodeURIComponent(f_to_remove));
    xhr.send(data);
}

const riga1 = document.getElementById("riga_orari_mattina");
const riga2 = document.getElementById("riga_orari_sera");

function removeInterval(ora_inizio, ora_fine) {
    let elements = document.getElementsByName('i' + ora_inizio + '-' + ora_fine);
    elements.forEach((element) => {
        element.parentNode.removeChild(element);
    });
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/removeInterval.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let oraInizio = ora_inizio.getHours();
            let oraFine = ora_fine.getHours();
            for(let i=oraInizio; i!=oraFine; i=(i+1)%24) {
                if(i<12) {
                    riga1.children.item(i+1).style.background = "none";
                } else {
                    riga2.children.item(i-11).style.background = "none";
                }
            }
            console.log('Fascia oraria rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della fascia oraria:', xhr.status);
        }
    };
    let data = encodeURI('orainizio=' + encodeURIComponent(ora_inizio) + '&orafine=' + encodeURIComponent(ora_fine));
    xhr.send(data);
}

function friendRequest(amico) {
    let element = document.getElementById('friend_request');
    element.innerHTML = "Richiesta inviata";
    element.onclick = null;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/friendRequest.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Richiesta inviata con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante invio richiesta:', xhr.status);
        }
    };
    let data = encodeURI('amico=' + encodeURIComponent(amico));
    xhr.send(data);
}

function addFollowed(neoseguito) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/addFollowed.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Seguito aggiunto con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante aggiunta seguito:', xhr.status);
        }
    };
    let data = encodeURI('neoseguito=' + encodeURIComponent(neoseguito));
    xhr.send(data);
}

function addBlocked(bloccato) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/addFollowed.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Blocco impostato con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante impostazione blocco:', xhr.status);
        }
    };
    let data = encodeURI('bloccato=' + encodeURIComponent(bloccato));
    xhr.send(data);
}

function removeFriend(examico) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/removeFriend.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Amicizia rescissa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rescissione amicizia:', xhr.status);
        }
    };
    let data = encodeURI('examico=' + encodeURIComponent(examico));
    xhr.send(data);
}

function removeFollowed(exseguito) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/removeFollowed.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Seguito lasciato con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rilascio seguito:', xhr.status);
        }
    };
    let data = encodeURI('exseguito=' + encodeURIComponent(exseguito));
    xhr.send(data);
}

function removeBlocked(perdonato) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/profile/removeBlocked.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Blocco sollevato con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione blocco:', xhr.status);
        }
    };
    let data = encodeURI('perdonato=' + encodeURIComponent(perdonato));
    xhr.send(data);
}

function modificaProfilo() {
    const alter_form = document.getElementsByName('alter_form');
    if(alter_form.hidden == true) {
        alter_form.hidden = false;
    } else {
        alter_form.hidden = true;
    }
}

function tabellaOrari(inizio, fine) {
    let oraInizio = inizio.getHours();
    let oraFine = fine.getHours();
    for(let i=oraInizio; i!=oraFine; i=(i+1)%24) {
        if(i<12) {
            riga1.children.item(i+1).style.background = "green";
        } else {
            riga2.children.item(i-11).style.background = "green";
        }
    }
}

function oraCorrente() {
    const intestazione = document.getElementById("intestazione_orari");
    let tempo_corrente = new Date();
    let ora_corrente = tempo_corrente.getHours();
    if(ora_corrente<=12) {
        intestazione.children.item(ora_corrente).style.color = "green";
        riga1.children.item(0).style.color = "green";
    } else {
        intestazione.children.item(ora_corrente-12).style.color = "green";
        riga2.children.item(0).style.color = "green";
    }
}
