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
    /* Handling remove-add friend button on top of the page, and cancel request*/
    const removeFriendButton = document.getElementById('remove_friend');
    if (removeFriendButton) {
        removeFriendButton.addEventListener('click', function() { removeFriend(username, other) });
    }
    const addFriendButton = document.getElementById('add_friend');
    if (addFriendButton) {
        addFriendButton.addEventListener('click', function() { requestFriend(username, other) });
    }
    const cancelRequestButton = document.getElementById('cancel_request');
    if (cancelRequestButton) {
        cancelRequestButton.addEventListener('click', function() { removeFriendRequest(username, other) });
    }
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
    const removeTimeIntervalButtons = document.getElementsByClassName('remove_timeslot_buttons');
    if (removeTimeIntervalButtons.length > 0) {
        for (i = 0; i < removeTimeIntervalButtons.length; i++) {
            let button = removeTimeIntervalButtons[i];
            let id = button.closest('li').id;
            let times = button.closest('li').innerHTML.split('<')[0].split(' - ');
            let start = times[0].trim();
            let end = times[1].trim();
            button.addEventListener('click', function() {removeTimeInterval(username, start, end, id) });
        }
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

function requestFriend(username, other) {
    const xhr = new XMLHttpRequest();
    const text = username + ' ti ha inviato una richiesta di amicizia!';
    const request = 1; // In MySQL, 1 means true for TINYINT(1)
    const read = 0; // In MySQL, 0 means false for TINYINT(1)
    const url = 'functions/requestFriend.php?username=' + encodeURIComponent(username) 
        + '&other=' + encodeURIComponent(other)
        + '&text=' + encodeURIComponent(text)
        + '&request=' + encodeURIComponent(request)
        + '&read=' + encodeURIComponent(read);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Richiesta di amicizia inviata con successo al server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante l\'invio della richiesta di amicizia:', xhr.status);
        }
    };
    xhr.send();
}

function removeFriendRequest(username, other) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/removeFriendRequest.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Richiesta di amicizia rimossa con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione della richiesta di amicizia:', xhr.status);
        }
    };
    xhr.send();
}
