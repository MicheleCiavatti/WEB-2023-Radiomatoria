const intestazione = document.getElementById("intestazione_orari").children;
const riga1 = document.getElementById("riga_orari_mattina").children;
const riga2 = document.getElementById("riga_orari_sera").children;
const removeTimeIntervalButtons = document.getElementsByClassName('remove_timeslot_buttons');

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
    let username = document.getElementById('session_user_name');
    let other = document.getElementById('profile_name').innerHTML;
    username = username === null ? other : username.innerHTML;
    /* Handling of follow buttons on top of the page */
    let addFollowButton = document.getElementById('follow_button');
    let removeFollowButton = document.getElementById('remove_follow');
    if (addFollowButton)
        addFollowButton.addEventListener('click', function() { addFollow(username, other) });
    if (removeFollowButton)
        removeFollowButton.addEventListener('click', function() { removeFollow(username, other) });
    /* Handling unfollow buttons in the list of followed */
    const removeFollowButtons = document.getElementsByClassName('remove_follow_buttons');
    if (removeFollowButtons.length > 0) {
        for (i = 0; i < removeFollowButtons.length; i++) {
            const button = removeFollowButtons[i];
            const other = button.parentElement.querySelector('a').innerHTML;
            button.addEventListener('click', function() { removeFollow(username, other) });
        }
    }
    /* Handling remove-add friend button on top of the page*/
    const removeFriendButton = document.getElementById('remove_friend');
    if (removeFriendButton) {
        removeFriendButton.addEventListener('click', function() { removeFriend(username, other) });
    }
    //TODO add friend button
    
    /* Handling remove friend buttons in the list of friends */
    const removeFriendButtons = document.getElementsByClassName('remove_friend_buttons');
    if (removeFriendButtons.length > 0) {
        for (i = 0; i < removeFriendButtons.length; i++) {
            const button = removeFriendButtons[i];
            const other = button.parentElement.querySelector('a').innerHTML;
            button.addEventListener('click', function() { removeFriend(username, other) });
        }
    }

    /*Handling remove frequency buttons */
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
