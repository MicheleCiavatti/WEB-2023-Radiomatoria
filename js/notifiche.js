const username = document.getElementById('pag_profilo').firstChild.innerHTML;
const unread_list = document.getElementById('unread_notifications_list');
const read_list = document.getElementById('read_notifications_list');
const total = document.getElementById("notifications_total");
const unread_total = document.getElementById("unread_total");
const read_total = document.getElementById("read_total");

function addNumbers() {
    let totale = 0;
    if(unread_list) {
        totale += unread_list.childElementCount;
        unread_total.innerHTML = "Da leggere " + unread_list.childElementCount;
    }
    if(read_list) {
        totale += read_list.childElementCount;
        read_total.innerHTML = "Lette " + read_list.childElementCount;
    }
    if(totale > 0) {
        total.innerHTML = "Le tue notifiche " + totale;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    /* Aesthetics */
    addNumbers();

    /* Handling notification reading */
    const readButtons = document.getElementsByClassName('readnotification');
    if (readButtons.length > 0) {
        for (i = 0; i < readButtons.length; i++) {
            let button = readButtons[i];
            let li = button.closest('li');
            button.addEventListener('click', function() { 
                button.parentNode.removeChild(button);
                readNotification(li);
            });
        }
    }
    /* Handling the refuse friendship buttons */
    const refuseFriendButtons = document.getElementsByClassName('friendrefuse');
    if (refuseFriendButtons.length > 0) {
        for (i = 0; i < refuseFriendButtons.length; i++) {
            let button = refuseFriendButtons[i];
            let li = button.closest('li');
            let other = li.querySelector('header h4 a').innerHTML;
            let nid = li.id.slice(3);
            button.addEventListener('click', function() {
                outcomeNotification(nid, other, "ha rifiutato la tua richiesta di amicizia");
            });
        }
    }
    /* Handling the accept friendship buttons */
    const acceptFriendButtons = document.getElementsByClassName('friendaccept');
    if (acceptFriendButtons.length > 0) {
        for (i = 0; i < acceptFriendButtons.length; i++) {
            let button = acceptFriendButtons[i];
            let li = button.closest('li');
            let other = li.querySelector('header h4 a').innerHTML;
            let nid = li.id.slice(3);
            button.addEventListener('click', function() { acceptFriend(other, nid) });
        }
    }
    /* Handling the remove notification buttons */
    const removeNotificationButtons = document.getElementsByClassName('removenotification');
    if (removeNotificationButtons.length > 0) {
        for (i = 0; i < removeNotificationButtons.length; i++) {
            let button = removeNotificationButtons[i];
            let li = button.closest('li');
            let nid = li.id.slice(3);
            button.addEventListener('click', function() {
                removeNotification(nid);
            });
        }
    }
});

function readNotification(li) {
    let nid = li.id.slice(3);

    let xhr = new XMLHttpRequest();
    let url = 'functions/readNotification.php?nid=' + encodeURIComponent(nid);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica segnata come letta dal server');
            li.setAttribute("name", "letta");
            if(read_list) {
                read_list.appendChild(li);
                if(empty(unread_list)) {
                    location.reload();
                }
                addNumbers();
            } else {
                location.reload();
            }
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la lettura della notifica:', xhr.status);
        }
    };
    xhr.send();
}

function acceptFriend(other, nid) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/acceptFriendRequest.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Richiesta di amicizia accettata con successo dal server');
            outcomeNotification(nid, other, "è diventato tuo amico");
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error("Errore durante l'accettazione della richiesta di amicizia:", xhr.status);
        }
    };
    xhr.send();
}

function removeNotification(nid) {
    let li = document.getElementById("nid" + nid);
    let list = li.parentNode;
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeNotification.php?nid=' + encodeURIComponent(nid);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            list.removeChild(li);
            if(list.childElementCount > 0) {
                addNumbers();
            } else {
                location.reload();
            }
                    console.log('Notifica rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della notifica:', xhr.status);
        }
    };
    xhr.send();
}

function outcomeNotification(nid, senderid, outcome) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/outcomeNotification.php?username=' + encodeURIComponent(username) + '&senderid=' + encodeURIComponent(senderid) + '&outcome=' + encodeURIComponent(outcome);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            removeNotification(nid);
            console.log('Risposta inviata con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la creazione della risposta:', xhr.status);
        }
    };
    xhr.send();
}