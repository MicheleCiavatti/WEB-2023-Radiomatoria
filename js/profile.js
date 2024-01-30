const intestazione = document.getElementById("intestazione_orari").children;
const riga1 = document.getElementById("riga_orari_mattina").children;
const riga2 = document.getElementById("riga_orari_sera").children;
const removeFrequencyButtons = document.getElementsByName('remove_frequency_buttons');
const removeTimeIntervalButtons = document.getElementsByName('remove_timeslot_buttons');
const owner = document.getElementById('profile_name').innerHTML;
const removeFriendButton = document.getElementsByName('remove_friend');
const removeFollowButton = document.getElementsByName('remove_follow');
const removeBlockButton = document.getElementsByName('remove_block');

function decorateTable(start, end, color) {
    let oraInizio = start.slice(0,2);
    let minutiInizio = start.slice(3,5);
    let oraFine = end.slice(0,2);
    let minutiFine = end.slice(3,5);
    for(item of intestazione.children) {
        if((oraInizio < parseInt(item.innerHTML)
        && oraFine > parseInt(item.innerHTML)
        && oraInizio < oraFine) || (oraInizio > oraFine
        && (oraInizio < parseInt(item.innerHTML) || oraFine > parseInt(item.innerHTML)))) {
            riga1.children.item(parseInt(item.innerHTML)*2-1).style.background = color;
            riga1.children.item(parseInt(item.innerHTML)*2).style.background = color;
        }
        if((oraInizio < parseInt(item.innerHTML) + 12
        && oraFine > parseInt(item.innerHTML) + 12
        && oraInizio < oraFine) || (oraInizio > oraFine
        && (oraInizio < parseInt(item.innerHTML) + 12 || oraFine > parseInt(item.innerHTML) + 12))) {
            riga2.children.item(parseInt(item.innerHTML)*2-1).style.background = color;
            riga2.children.item(parseInt(item.innerHTML)*2).style.background = color;
        }
    }
    if(minutiInizio < 30) {
        if(oraInizio <= 12) {
            riga1.children.item(oraInizio*2).style.background = color;
            if(minutiInizio == 0) {
                riga1.children.item(oraInizio*2-1).style.background = color;
            }
        } else {
            riga2.children.item(oraInizio*2+1).style.background = color;
            if(minutiInizio == 0) {
                riga2.children.item(oraInizio*2-1).style.background = color;
            }
        }
    }
    if(minutiFine > 30) {
        if(oraFine <= 12) {
            riga1.children.item(oraFine*2-1).style.background = color;
        } else {
            riga2.children.item(oraFine*2-1).style.background = color;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById("comandi")) {
        /* Handling of buttons in the top and bottom of the page */
        const username = document.getElementById('session_user_name').innerHTML;
        const addFriendButton = document.getElementById('friend_request');
        if (addFriendButton) {
            addFriendButton.addEventListener('click', function() { notify(username, owner, true) });
        }
        if (removeFriendButton.length > 0) {
            removeFriendButton[0].addEventListener('click', function() { removeFriend(username, owner) });
        }
        const addFollowButton = document.getElementById('follow_button');
        if (addFollowButton) {
            addFollowButton.addEventListener('click', function() {
                addFollow(username, owner);
                notify(username, owner, false);
            });
        }
        if (removeFollowButton.length > 0) {
            removeFollowButton[0].addEventListener('click', function() { removeFollow(username, owner) });
        }
        const addBlockButton = document.getElementById('block_button');
        if (addBlockButton) {
            addBlockButton.addEventListener('click', function() { addBlock(username, owner) });
        }
        if (removeBlockButton.length > 0) {
            removeBlockButton[0].addEventListener('click', function() { removeBlocked(username, owner) });
        }
    } else {
        /* Handling of buttons in the bottom of the page only */
        if (removeFriendButton.length > 0) {
            for (i = 0; i < removeFriendButton.length; i++) {
                let button = removeFriendButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeFriend(owner, other) });
            }
        }
        if (removeFollowButton.length > 0) {
            for (i = 0; i < removeFollowButton.length; i++) {
                let button = removeFollowButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeFollow(owner, other) });
            }
        }
        if (removeBlockButton.length > 0) {
            for (i = 0; i < removeBlockButton.length; i++) {
                let button = removeBlockButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeBlocked(owner, other) });
            }
        }
    }

    /*Handling remove frequency buttons */
    if (removeFrequencyButtons.length > 0) {
        for (i = 0; i < removeFrequencyButtons.length; i++) {
            let button = removeFrequencyButtons[i];
            let id = button.parentElement.id;
            let f_to_remove = button.parentElement.innerText;
            button.addEventListener('click', function() {removeFrequency(f_to_remove, username, id) });
        }
    }

    /* Handling remove time interval buttons */
    if (removeTimeIntervalButtons.length > 0) {
        for (i = 0; i < removeTimeIntervalButtons.length; i++) {
            let button = removeTimeIntervalButtons[i];
            let id = button.parentElement.id;
            let times = button.parentElement.innerText.split('<')[0].split(' - ');
            let start = times[0].trim();
            let end = times[1].trim();
            button.addEventListener('click', function() {removeTimeInterval(username, start, end, id) });

            /* Aesthetics */
            decorateTable(start, end, "green");
        }
    }
    let tempo_corrente = new Date();
    let ora_corrente = tempo_corrente.getHours();
    if(ora_corrente<=12) {
        document.getElementById("intestazione_orari").children.item(ora_corrente).style.color = "green";
        riga1.children.item(0).style.color = "blue";
    } else {
        document.getElementById("intestazione_orari").children.item(ora_corrente-12).style.color = "green";
        riga2.children.item(0).style.color = "blue";
    }
});

function removeFrequency(f_to_remove, id) {
    let element = document.getElementById(id);
    element.parentNode.removeChild(element);
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeFrequency.php?f_to_remove=' + encodeURIComponent(f_to_remove) + '&username=' + encodeURIComponent(owner);
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

function removeTimeInterval(start, end, id) {
    let element = document.getElementById(id);
    element.parentNode.removeChild(element);
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeTimeSlot.php?username=' + encodeURIComponent(owner) + '&start=' + encodeURIComponent(start) + '&end=' + encodeURIComponent(end);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            decorateTable(start, end, "none");
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

function removeFriend(username, other) {
    console.log(username + ' ' + other);
    const xhr = new XMLHttpRequest();
    const url = 'functions/removeFriend.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Amico rimosso con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione dell\'amico:', xhr.status);
        }
    };
    xhr.send();
}

function decorateTable(start, end, color) {
    let oraInizio = start.slice(0,2);
    let minutiInizio = start.slice(3,5);
    let oraFine = end.slice(0,2);
    let minutiFine = end.slice(3,5);
    for(item of intestazione) {
        if((oraInizio < parseInt(item.innerHTML)
        && oraFine > parseInt(item.innerHTML)
        && oraInizio < oraFine) || (oraInizio > oraFine
        && (oraInizio < parseInt(item.innerHTML) || oraFine > parseInt(item.innerHTML)))) {
            riga1.item(parseInt(item.innerHTML)*2-1).style.background = color;
            riga1.item(parseInt(item.innerHTML)*2).style.background = color;
        }
        if((oraInizio < parseInt(item.innerHTML) + 12
        && oraFine > parseInt(item.innerHTML) + 12
        && oraInizio < oraFine) || (oraInizio > oraFine
        && (oraInizio < parseInt(item.innerHTML) + 12 || oraFine > parseInt(item.innerHTML) + 12))) {
            riga2.item(parseInt(item.innerHTML)*2-1).style.background = color;
            riga2.item(parseInt(item.innerHTML)*2).style.background = color;
        }
    }
    if(minutiInizio < 30) {
        if(oraInizio <= 12) {
            riga1.item(oraInizio*2).style.background = color;
            if(minutiInizio == 0) {
                riga1.item(oraInizio*2-1).style.background = color;
            }
        } else {
            riga2.item((oraInizio-12)*2).style.background = color;
            if(minutiInizio == 0) {
                riga2.item((oraInizio-12)*2-1).style.background = color;
            }
        }
    }
    if(minutiFine > 30) {
        if(oraFine <= 12) {
            riga1.item(oraFine*2-1).style.background = color;
        } else {
            riga2.item((oraFine-12)*2-1).style.background = color;
        }
    }
}
