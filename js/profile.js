const intestazione = document.getElementById("intestazione_orari");
const riga1 = document.getElementById("riga_orari_mattina");
const riga2 = document.getElementById("riga_orari_sera");
const removeTimeIntervalButtons = document.getElementsByClassName('remove_timeslot_buttons');

document.addEventListener('DOMContentLoaded', function() {
    let username = document.getElementById('session_user_name');
    let other = document.getElementById('profile_name').innerHTML;
    username = username === null ? other : username.innerHTML;
    /* Handling of follow buttons */
    let addFollowButton = document.getElementById('follow_button');
    let removeFollowButton = document.getElementById('remove_follow');
    if (addFollowButton)
        addFollowButton.addEventListener('click', function() { addFollow(username, other) });
    if (removeFollowButton)
        removeFollowButton.addEventListener('click', function() { removeFollow(username, other) });
    /* Handling remove frequency buttons */
    let removeFrequencyButtons = document.getElementsByClassName('remove_frequency_buttons');
    if (removeFrequencyButtons.length > 0) {
        for (i = 0; i < removeFrequencyButtons.length; i++) {
            let button = removeFrequencyButtons[i];
            let id = button.closest('li').id;
            let f_to_remove = button.closest('li').innerHTML;
            button.addEventListener('click', function() {removeFrequency(f_to_remove, username, id) });
        }
    }
    /* Handling remove time interval buttons */
    if (removeTimeIntervalButtons.length > 0) {
        for (i = 0; i < removeTimeIntervalButtons.length; i++) {
            let button = removeTimeIntervalButtons[i];
            let id = button.closest('li').id;
            let times = button.closest('li').innerHTML.split('<')[0].split(' - ');
            let start = times[0].trim();
            let end = times[1].trim();
            button.addEventListener('click', function() {removeTimeInterval(username, start, end, id) });

            /* Aesthetics */
            let oraInizio = start.slice(0,2);
            let oraFine = end.slice(0,2);
            for(item of intestazione.children) {
                if((oraInizio <= parseInt(item.innerHTML)
                && oraFine >= parseInt(item.innerHTML)
                && oraInizio <= oraFine) || (oraInizio > oraFine
                && (oraInizio <= parseInt(item.innerHTML) || oraFine >= parseInt(item.innerHTML)))) {
                    riga1.children.item(parseInt(item.innerHTML)).style.background = "green";
                }
                if((oraInizio <= parseInt(item.innerHTML) + 12
                && oraFine >= parseInt(item.innerHTML) + 12
                && oraInizio <= oraFine) || (oraInizio > oraFine
                && (oraInizio <= parseInt(item.innerHTML) + 12 || oraFine >= parseInt(item.innerHTML) + 12))) {
                    riga2.children.item(parseInt(item.innerHTML)).style.background = "green";
                }
            }
        }
    }
    let tempo_corrente = new Date();
    let ora_corrente = tempo_corrente.getHours();
    if(ora_corrente<=12) {
        document.getElementById("intestazione_orari").children.item(ora_corrente).style.color = "green";
        riga1.children.item(0).style.color = "green";
    } else {
        document.getElementById("intestazione_orari").children.item(ora_corrente-12).style.color = "green";
        riga2.children.item(0).style.color = "green";
    }
});

function removeFrequency(f_to_remove, username, id) {
    let element = document.getElementById(id);
    element.parentNode.removeChild(element);
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeFrequency.php?f_to_remove=' + encodeURIComponent(f_to_remove) + '&username=' + encodeURIComponent(username);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Frequenza rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della frequenza:', xhr.status);
        }
    };
    xhr.send();
}

function removeTimeInterval(username, start, end, id) {
    let element = document.getElementById(id);
    element.parentNode.removeChild(element);
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeTimeSlot.php?username=' + encodeURIComponent(username) + '&start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let oraInizio = start.slice(0,2);
            let oraFine = end.slice(0,2);
            for (i = 0; i < removeTimeIntervalButtons.length; i++) {
                if((removeTimeIntervalButtons[i].closest('li').innerHTML.split('<')[0].split(' - '))[1].trim().slice(0,2) == oraInizio) {
                    let overlapInizio = true;
                    if(typeof overlapFine !== 'undefined') { break; }
                }
                if((removeTimeIntervalButtons[i].closest('li').innerHTML.split('<')[0].split(' - '))[0].trim().slice(0,2) == oraFine) {
                    let overlapFine = true;
                    if(typeof overlapInizio !== 'undefined') { break; }
                }
            }
            for(item of intestazione.children) {
                if((oraInizio <= parseInt(item.innerHTML)
                && oraFine >= parseInt(item.innerHTML)
                && oraInizio < oraFine) || (oraInizio > oraFine
                && (oraInizio <= parseInt(item.innerHTML) || oraFine >= parseInt(item.innerHTML)))) {
                    if(!((typeof overlapInizio !== 'undefined' && parseInt(item.innerHTML) == oraInizio) || 
                    (typeof overlapFine !== 'undefined' && parseInt(item.innerHTML) == oraFine))) {
                        riga1.children.item(parseInt(item.innerHTML)).style.background = "none";
                    }
                }
                if((oraInizio <= parseInt(item.innerHTML) + 12
                && oraFine >= parseInt(item.innerHTML) + 12
                && oraInizio < oraFine) || (oraInizio > oraFine
                && (oraInizio <= parseInt(item.innerHTML) + 12 || oraFine >= parseInt(item.innerHTML) + 12))) {
                    if(!((typeof overlapInizio !== 'undefined' && parseInt(item.innerHTML) == oraInizio) || 
                    (typeof overlapFine !== 'undefined' && parseInt(item.innerHTML) == oraFine))) {
                        riga2.children.item(parseInt(item.innerHTML)).style.background = "none";
                    }
                }
            }
            console.log('Fascia oraria rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della fascia oraria:', xhr.status);
        }
    };
    xhr.send();
}

function addFollow(username, other) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addFollow.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Follow aggiunto con successo al server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante l\'aggiunta del follow:', xhr.status);
        }
    };
    xhr.send();
}

function removeFollow(username, other) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeFollow.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Follow rimosso con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione del follow:', xhr.status);
        }
    };
    xhr.send();
}

//onclick="removeFrequency('<?= $f?>', '<?= $utente['NomeUtente']?>', '<?= '#f' . str_replace('.', '_', $f)?>')"