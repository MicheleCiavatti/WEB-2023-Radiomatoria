document.addEventListener('DOMContentLoaded', function() {
    const username = document.getElementById('session_user_name').innerHTML;
    /* Handling the refuse friendship buttons */
    const refuseFriendButtons = document.getElementsByClassName('friendrefuse');
    if (refuseFriendButtons.length > 0) {
        for (i = 0; i < refuseFriendButtons.length; i++) {
            const button = refuseFriendButtons[i];
            let section = button.closest('section');
            const other = section.querySelector('header h3 a').innerHTML;
            button.addEventListener('click', function() { refuseFriend(username, other, section) });
        }
    }
    /* Handling the accept friendship buttons */
    const acceptFriendButtons = document.getElementsByClassName('friendaccept');
    if (acceptFriendButtons.length > 0) {
        for (i = 0; i < acceptFriendButtons.length; i++) {
            const button = acceptFriendButtons[i];
            let section = button.closest('section');
            const other = section.querySelector('header h3 a').innerHTML;
            button.addEventListener('click', function() { acceptFriend(username, other, section) });
        }
    }
    /* Handling the remove notification buttons */
    const removeNotificationButtons = document.getElementsByClassName('removenotification');
    if (removeNotificationButtons.length > 0) {
        for (i = 0; i < removeNotificationButtons.length; i++) {
            const button = removeNotificationButtons[i];
            const section = button.closest('section');
            const other = section.querySelector('header h3 a').innerHTML;
            const nid = button.previousElementSibling.value;
            button.addEventListener('click', function() { removeNotification(username, other, section, nid) });
        }
    }
});

function refuseFriend(username, other, section) {
    section.innerHTML = `<p>Hai rifiutato la richiesta di amicizia di ${other}</p>`
    const xhr = new XMLHttpRequest();
    const url = 'functions/refuseFriendRequest.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Richiesta di amicizia rifiutata con successo dal server');
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error('Errore durante il rifiuto della richiesta di amicizia:', xhr.status);
        }
    };
    xhr.send();
}

function acceptFriend(username, other, section) {
    section.innerHTML = `<p>Hai accettato la richiesta di amicizia di ${other}</p>`
    const xhr = new XMLHttpRequest();
    const url = 'functions/acceptFriendRequest.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Richiesta di amicizia accettata con successo dal server');
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error('Errore durante l\'accettazione della richiesta di amicizia:', xhr.status);
        }
    };
    xhr.send();
}

function removeNotification(username, other, section, nid) {
    section.innerHTML = `<p>Notifica da ${other} rimossa</p>`
    const xhr = new XMLHttpRequest();
    const url = 'functions/removeNotification.php?username=' + encodeURIComponent(username) 
                + '&other=' + encodeURIComponent(other)
                + '&nid=' + encodeURIComponent(nid);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Notifica rimossa con successo dal server');
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error('Errore durante la rimozione della notifica:', xhr.status);
        }
    };
    xhr.send();
}
