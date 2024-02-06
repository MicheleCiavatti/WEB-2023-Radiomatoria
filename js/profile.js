const pid = new URLSearchParams(window.location.search).get('pid');
if (pid) document.getElementById(pid).scrollIntoView({ behavior: 'smooth' });
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
            const other = button.parentElement.parentElement.querySelector('a').innerHTML;
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
            const other = button.parentElement.parentElement.querySelector('a').innerHTML;
            button.addEventListener('click', function() { removeFriend(username, other) });
        }
    }
    /*Handling remove frequency buttons */
    const removeFrequencyButtons = document.getElementsByClassName('remove_frequency_buttons');
    if (removeFrequencyButtons.length > 0) {
        for (i = 0; i < removeFrequencyButtons.length; i++) {
            const button = removeFrequencyButtons[i];
            const id = button.parentElement.parentElement.id;
            const f_to_remove = button.parentElement.previousElementSibling.innerHTML;
            button.addEventListener('click', function() {removeFrequency(f_to_remove, username, id) });
        }
    }
    /* Handling remove time interval buttons */
    const removeTimeIntervalButtons = document.getElementsByClassName('remove_timeslot_buttons');
    if (removeTimeIntervalButtons.length > 0) {
        for (i = 0; i < removeTimeIntervalButtons.length; i++) {
            const button = removeTimeIntervalButtons[i];
            const id = button.closest('li').id;
            const start = button.previousElementSibling.value;
            const end = button.nextElementSibling.value;
            button.addEventListener('click', function() {removeTimeInterval(username, start, end, id) });
        }
    }
    /* Handling like and unlike buttons */
    const likeButtons = document.getElementsByClassName('like_button');
    if (likeButtons.length > 0) {
        for (i = 0; i < likeButtons.length; i++) {
            const button = likeButtons[i];
            const post_author = button.previousElementSibling.value;
            const post_number = button.nextElementSibling.value;
            button.addEventListener('click', function() { likePost(post_author, post_number, username) });
        }
    }
    const unlikeButtons = document.getElementsByClassName('unlike_button');
    if (unlikeButtons.length > 0) {
        for (i = 0; i < unlikeButtons.length; i++) {
            const button = unlikeButtons[i];
            const post_author = button.previousElementSibling.value;
            const post_number = button.nextElementSibling.value;
            button.addEventListener('click', function() { unlikePost(post_author, post_number, username) });
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
    const read = null; // The read is only used for posts, not for friend requests
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

function likePost(post_author, post_number, liker) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/likePost.php?post_author=' + encodeURIComponent(post_author) 
                + '&post_number=' + encodeURIComponent(post_number)
                + '&liker=' + encodeURIComponent(liker);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Post liked successfully');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Error while liking the post:', xhr.status);
        }
    };
    xhr.send();
}

function unlikePost(post_author, post_number, unliker) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/unlikePost.php?post_author=' + encodeURIComponent(post_author) 
                + '&post_number=' + encodeURIComponent(post_number)
                + '&unliker=' + encodeURIComponent(unliker);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Post unliked successfully');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Error while unliking the post:', xhr.status);
        }
    };
    xhr.send();
}
