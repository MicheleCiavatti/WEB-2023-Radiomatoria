const username = document.getElementById('session_user_name').innerHTML;
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
            let article = button.closest('article');
            button.addEventListener('click', function() { 
                button.parentNode.removeChild(button);
                readNotification(article);
            });
        }
    }
    /* Handling the refuse and accept friendship buttons */
    const refuseFriendButtons = document.getElementsByClassName('friendrefuse');
    const acceptFriendButtons = document.getElementsByClassName('friendaccept');
    if (acceptFriendButtons.length > 0) {
        for (i = 0; i < refuseFriendButtons.length; i++) {
            let refuse_button = refuseFriendButtons[i];
            let article = refuse_button.closest('article');
            let other = refuse_button.closest('ul').className.slice(11);
            let nid = article.id;

            refuse_button.addEventListener('click', function() {
                outcomeNotification(other, "ha rifiutato la tua richiesta di amicizia");
                removeNotification(nid);
            });
            let accept_button = acceptFriendButtons[i];
            accept_button.addEventListener('click', function() { acceptFriend(other, nid) });
        }
    }
    /* Handling the remove notification buttons */
    const removeNotificationButtons = document.getElementsByClassName('removenotification');
    if (removeNotificationButtons.length > 0) {
        for (i = 0; i < removeNotificationButtons.length; i++) {
            let button = removeNotificationButtons[i];
            let article = button.closest('article');
            let nid = article.id;
            button.addEventListener('click', function() {
                removeNotification(nid);
            });
        }
    }
    /* Handling redirect buttons */
    const redirectButtons = document.getElementsByClassName('redirect_post');
    if (redirectButtons.length > 0) {
        for (i = 0; i < redirectButtons.length; i++) {
            let button = redirectButtons[i];
            const pid = button.previousElementSibling.value;
            button.addEventListener('click', function() {
                window.location.href = '../php/profile.php?id=' + username + '&pid=Post_' + username + '_' + pid;
            });
        }
    }
});

function readNotification(article) {
    let nid = article.id.slice(3);

    let xhr = new XMLHttpRequest();
    let url = 'functions/readNotification.php?nid=' + encodeURIComponent(nid);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica segnata come letta dal server');
            location.reload();
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
            outcomeNotification(other, "è diventato tuo amico");
            removeNotification(nid);
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error("Errore durante l'accettazione della richiesta di amicizia:", xhr.status);
        }
    };
    xhr.send();
}

function removeNotification(nid) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeNotification.php?nid=' + encodeURIComponent(nid.slice(3));
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica rimossa con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della notifica:', xhr.status);
        }
    };
    xhr.send();
}

function outcomeNotification(senderid, outcome) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/outcomeNotification.php?username=' + encodeURIComponent(username) + '&senderid=' + encodeURIComponent(senderid) + '&outcome=' + encodeURIComponent(outcome);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Risposta inviata con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la creazione della risposta:', xhr.status);
        }
    };
    xhr.send();
}
