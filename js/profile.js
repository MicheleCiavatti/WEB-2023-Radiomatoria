const intestazione = document.getElementById("intestazione_orari").children;
const riga1 = document.getElementById("riga_orari_mattina").children;
const riga2 = document.getElementById("riga_orari_sera").children;
const removeFrequencyButtons = document.getElementsByName('remove_frequency_buttons');
const timeIntervals = document.getElementsByClassName('timeslots');

const owner = document.getElementById('profile_name').innerHTML;
const removeFriendButton = document.getElementsByName('remove_friend_button');
const removeFollowButton = document.getElementsByName('remove_follow_button');
const removeBlockButton = document.getElementsByName('remove_block_button');

const change_button_1 = document.getElementById("change_public_fields");
const public_fields = document.getElementById("change_fields_form");
const change_button_2 = document.getElementById("change_private_fields");
const private_fields = document.getElementById("private_fields");
const add_post_button = document.getElementById('add_post_button');
const add_post = document.getElementById('add_post_form');
const show_comments_buttons = document.getElementsByName("show_comments");
const remove_post_button = document.getElementsByName("remove_post");
const remove_comment_button = document.getElementsByName("remove_comment");

const add_comment = document.getElementById("add_comment_form");
const comment_post_button = document.getElementsByName("comment_post");
const respond_comment_button = document.getElementsByName("answer_comment");
const comment_reset = document.getElementById("comment_reset");

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

document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById("comandi")) {
        /* Handling of buttons in the top of the page */
        const username = document.getElementById('session_user_name').innerHTML;
        const addFriendButton = document.getElementById('friend_request');
        if (addFriendButton) {
            addFriendButton.addEventListener('click', function() { notify(true) });
        }
        if (removeFriendButton.length > 0) {
            removeFriendButton[0].addEventListener('click', function() { removeFriend(username, owner) });
        }
        const addFollowButton = document.getElementById('follow_button');
        if (addFollowButton) {
            addFollowButton.addEventListener('click', function() {
                addFollow();
                notify(false);
            });
        }
        if (removeFollowButton.length > 0) {
            removeFollowButton[0].addEventListener('click', function() { removeFollow(username, owner) });
        }
        const addBlockButton = document.getElementById('block_button');
        if (addBlockButton) {
            addBlockButton.addEventListener('click', function() { addBlock() });
        }
        if (removeBlockButton.length > 0) {
            removeBlockButton[0].addEventListener('click', function() { removeBlocked(username, owner) });
        }
    } else {
        /* Handling of buttons in the bottom of the page */
        if (removeFriendButton.length > 0) {
            for (i = 0; i < removeFriendButton.length; i++) {
                let button = removeFriendButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeFriend(other) });
            }
        }
        if (removeFollowButton.length > 0) {
            for (i = 0; i < removeFollowButton.length; i++) {
                let button = removeFollowButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeFollow(other) });
            }
        }
        if (removeBlockButton.length > 0) {
            for (i = 0; i < removeBlockButton.length; i++) {
                let button = removeBlockButton[i];
                let other = button.parentElement.id;
                button.addEventListener('click', function() { removeBlocked(other) });
            }
        }
    }

    /*Handling remove frequency buttons */
    if (removeFrequencyButtons.length > 0) {
        for (i = 0; i < removeFrequencyButtons.length; i++) {
            let button = removeFrequencyButtons[i];
            let id = button.parentElement.id;
            let f_to_remove = button.parentElement.innerText;
            button.addEventListener('click', function() {removeFrequency(f_to_remove, id) });
        }
    }

    /* Handling remove time interval buttons */
    if (timeIntervals.length > 0) {
        for (i = 0; i < timeIntervals.length; i++) {
            let times = timeIntervals[i].innerText.split('<')[0].split(' - ');
            let start = times[0].trim();
            let end = times[1].trim();

            if(timeIntervals[i].lastChild.type = "button") {
                let id = timeIntervals[i].id;
                let button = timeIntervals[i].lastChild;
                button.addEventListener('click', function() {removeTimeInterval(start, end, id) });
            }

            /* Aesthetics */
            decorateTable(start, end, "green");
        }
    }
    let tempo_corrente = new Date();
    let ora_corrente = tempo_corrente.getHours();
    if(ora_corrente<=12) {
        intestazione.item(ora_corrente).style.color = "green";
        riga1.item(0).style.color = "blue";
    } else {
        intestazione.item(ora_corrente-12).style.color = "green";
        riga2.item(0).style.color = "blue";
    }

    add_post_button.addEventListener("click", function() { toggle(add_post) });
    add_post.style.display = "none";
    change_button_1.addEventListener("click", function() { toggle(public_fields) });
    public_fields.style.display = "none";
    change_button_2.addEventListener("click", function() { toggle(private_fields) });
    private_fields.style.display = "none";
    
    if(show_comments_buttons.length > 0) {
        for(i=0; i<show_comments_buttons.length; i++) {
            let button = show_comments_buttons[i];
            let pid = button.id.slice(5);
            cid = pid + "_comment_list";
            button.addEventListener("click", function() {
                toggle(cid);
                if (button.innerText == "Mostra commenti") {
                    button.innerText = "Nascondi commenti";
                } else {
                    button.innerText = "Mostra commenti";
                }
            });
        }
    }
    if(comment_post_button.length > 0) {
        for(i=0; i<comment_post_button.length; i++) {
            let button = comment_post_button[i];
            let pid = button.id.slice(8);
            let author = button.parentNode.parentNode.firstChild.lastChild.firstChild.innerText;
            button.addEventListener("click", function() { mostraFormCommenti(pid, author, false); });
        }
    }
    if(respond_comment_button.length > 0) {
        for(i=0; i<respond_comment_button.length; i++) {
            let button = respond_comment_button[i];
            let author = button.parentNode.children.item[2].firstChild.innerText;
            let cid = button.id.slice(8);
            button.addEventListener("click", function() { removeComment(cid, author, true); });
        }
    }
    comment_reset.addEventListener("click", function() { toggle(add_comment); });

    add_comment.style.display = "none";
    if(remove_post_button.length > 0) {
        for(i=0; i<remove_post_button.length; i++) {
            let button = remove_post_button[i];
            let pid = button.id.slice(7);
            button.addEventListener("click", function() { removePost(pid); });
        }
    }
    if(remove_comment_button.length > 0) {
        for(i=0; i<remove_comment_button.length; i++) {
            let button = remove_comment_button[i];
            let cid = button.id.slice(7);
            button.addEventListener("click", function() { removeComment(cid); });
        }
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
    let url = 'functions/removeTimeSlot.php?username=' + encodeURIComponent(owner) + '&start=' + encodeURIComponent(start);
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

function addFollow() {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addFollow.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(owner);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Follow aggiunto con successo al server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'aggiunta del follow:", xhr.status);
        }
    };
    xhr.send();
}

function addBlock() {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addBlock.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(owner);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Blocco aggiunto con successo al server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'aggiunta del blocco:", xhr.status);
        }
    };
    xhr.send();
}

function removeFollow(other) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeFollow.php?username=' + encodeURIComponent(owner) + '&other=' + encodeURIComponent(other);
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

function removeFriend(other) {
    console.log(owner + ' ' + other);
    const xhr = new XMLHttpRequest();
    const url = 'functions/removeFriend.php?username=' + encodeURIComponent(owner) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Amico rimosso con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante la rimozione dell'amico:", xhr.status);
        }
    };
    xhr.send();
}

function notify(request) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/notify.php?username=' + encodeURIComponent(username) + '&receiver=' + encodeURIComponent(owner) + '&request=' + encodeURIComponent(request);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Follow aggiunto con successo al server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'aggiunta del follow:", xhr.status);
        }
    };
    xhr.send();
}

function removePost(pid) {
    let xhr = new XMLHttpRequest();
    let url = 'php/home/removePost.php?pid=' + encodeURIComponent(pid);
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    post_to_remove = document.getElementById('post' + pid);
    post_to_remove.parentElement.removeChild(post_to_remove);
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            comments_to_remove = xhr.response;
            comments_to_remove.array.forEach(element => {
                removeComment(element[0]);
            });
            location.reload();
            console.log('Post rimosso con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione post:', xhr.status);
        }
    };
    xhr.send();
}

function removeComment(cid) {
    let xhr = new XMLHttpRequest();
    let url = 'php/home/removeComment.php?cid=' + encodeURIComponent(cid);
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    comment_to_remove = document.getElementById('comment' + cid);
    if(isset(comment_to_remove)) {
        comment_to_remove.parentElement.removeChild(comment_to_remove);
    }
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Commento rimosso con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione commento:', xhr.status);
        }
    };
    xhr.send();
}

function toggle(element) {
    if (element.style.display == "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

function mostraFormCommenti(element_id, author, risposta) {
    toggle(add_comment);
    add_comment.children.item[3].innerHTML = element_id;
    add_comment.children.item[4].innerHTML = author;
    if(risposta) {
        add_comment.children.item[2].placeholder = "Risposta al commento di " + author;
    } else {
        add_comment.children.item[2].placeholder = "Commento al post di " + author;
    }
}
