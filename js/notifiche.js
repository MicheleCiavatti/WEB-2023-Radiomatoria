const read_list = document.getElementById('read_notifications_list')

function readNotification(nid) {
    let element = document.querySelector('#nid' + nid);
    element.removeAttribute("onclick");
    read_list.appendChild(element);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/notifiche/readNotification.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica segnata come letta dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la lettura della notifica:', xhr.status);
        }
    };
    let data = encodeURI('nid=' + encodeURIComponent(nid));
    xhr.send(data);
}

function removeNotification(nid) {
    let element = document.querySelector('#nid' + nid);
    element.parentNode.removeChild(element);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/notifiche/removeNotification.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la lettura della notifica:', xhr.status);
        }
    };
    let data = encodeURI('nid=' + encodeURIComponent(nid));
    xhr.send(data);
}

function outcomeNotification(nid, senderid, outcome) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/notifiche/outcomeNotification.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            removeNotification(nid);
            console.log('Risposta inviata con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la creazione della risposta:', xhr.status);
        }
    };
    let data = encodeURI('nid=' + encodeURIComponent(nid) + '&senderid=' + encodeURIComponent(senderid) + '&outcome=' + encodeURIComponent(outcome));
    xhr.send(data);
}

function addFriend(nid, senderid) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'functions/notifiche/addFriend.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            outcomeNotification(nid, senderid, "ha accettato la tua richiesta di amicizia");
            console.log('Amico aggiunto con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante aggiunta amico:', xhr.status);
        }
    };
    let data = encodeURI('senderid=' + encodeURIComponent(senderid));
    xhr.send(data);
}

function count() {
    document.getElementById("notifications_total").innerHTML += document.getElementById("unread_notifications_list").childElementCount + document.getElementById("read_notifications_list").childElementCount;
}

function countRead() {
    document.getElementById("pag_notifiche").firstChild.innerHTML += document.getElementById("unread_notifications_list").childElementCount;
}
