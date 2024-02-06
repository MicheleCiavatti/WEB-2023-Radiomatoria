/* For redirect function */
const pid = new URLSearchParams(window.location.search).get('pid');
if (pid) document.getElementById(pid).scrollIntoView({ behavior: 'smooth' });

const intestazione = document.getElementById("intestazione_orari").children;
const riga1 = document.getElementById("riga_orari_mattina").children;
const riga2 = document.getElementById("riga_orari_sera").children;
const removeFrequencyButtons = document.getElementsByName('remove_frequency_buttons');
const removeTimeSlotButtons = document.getElementsByName('remove_timeslot_buttons');
const timeIntervals = document.getElementsByClassName('timeslots');

const owner = document.getElementById('profile_name').innerHTML;
const removeFriendButton = document.getElementsByName('remove_friend_buttons');
const removeFollowButton = document.getElementsByName('remove_follow_buttons');
const removeBlockButton = document.getElementsByName('remove_block_buttons');

const show_comments_buttons = document.getElementsByName("show_comments");
const comment_lists = document.getElementsByName("comment_list");
const remove_post_button = document.getElementsByName("remove_post");
const remove_comment_button = document.getElementsByName("remove_comment");

const comment_post_button = document.getElementsByName("comment_post");
const respond_comment_button = document.getElementsByName("answer_comment");

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
            addFriendButton.addEventListener('click', function() { notify(username, 1, "ti ha inviato una richiesta di amicizia") });
        }
        const cancelRequestButton = document.getElementById('cancel_request');
        if (cancelRequestButton) {
            cancelRequestButton.addEventListener('click', function() { removeFriendRequest(username) });
        }
        if (removeFriendButton.length > 0) {
            removeFriendButton[0].addEventListener('click', function() { removeFriend(username, owner) });
        }
        const addFollowButton = document.getElementById('follow_button');
        if (addFollowButton) {
            addFollowButton.addEventListener('click', function() {
                addFollow(username);
            });
        }
        if (removeFollowButton.length > 0) {
            removeFollowButton[0].addEventListener('click', function() { removeFollow(username, owner) });
        }
        const addBlockButton = document.getElementById('block_button');
        if (addBlockButton) {
            addBlockButton.addEventListener('click', function() { addBlock(username) });
        }
        if (removeBlockButton.length > 0) {
            removeBlockButton[0].addEventListener('click', function() { removeBlock(username, owner) });
        }
    } else {
        /* Handling of buttons in the bottom of the page */
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
                button.addEventListener('click', function() { removeBlock(owner, other) });
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

            if(removeTimeSlotButtons.length > 0) {
                let id = timeIntervals[i].id;
                let button = removeTimeSlotButtons[i];
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

    /* Handling profile modification */
    const change_button_1 = document.getElementById("change_public_fields");
    if(change_button_1) {
        const public_fields = document.getElementById("change_fields_form");
        const change_button_2 = document.getElementById("change_private_fields");
        const private_fields = document.getElementById("private_fields");
        
        change_button_1.addEventListener("click", function() { toggle(public_fields) });
        public_fields.style.display = "none";
        change_button_2.addEventListener("click", function() { toggle(private_fields) });
        private_fields.style.display = "none";
    }
    
    /* Handling post addition */
    const user = document.getElementById("pag_profilo");
    if(user) {
        const add_post = document.getElementById('add_post_form');
        const add_post_button = document.getElementById('add_post_button');
    
        add_post_button.addEventListener("click", function() { toggle(add_post) });
        add_post.style.display = "none";

        /* Likes and dislikes */
        const like_buttons = document.getElementsByName("like_button");
        const dislike_buttons = document.getElementsByName("dislike_button");
        const author = user.firstChild.href.split("=")[1];
        for(i = 0; i < like_buttons.length; i++) {
            let like_button = like_buttons[i];
            if(like_button.className == "preferred_button") {
                decorateLike(like_button.id);
            }
            let element_id = like_button.id.slice(12);
            let post_id = element_id.split("_")[0];
            let creator = element_id.split("_")[1];
            let comment_id = element_id.split("_")[2];
            like_button.addEventListener("click", function() { like(author, post_id, creator, comment_id); });

            let dislike_button = dislike_buttons[i];
            if(dislike_button.className == "preferred_button") {
                decorateDislike(dislike_button.id);
            }
            dislike_button.addEventListener("click", function() { dislike(author, post_id, creator, comment_id); });
        }
    }

    /* Handling comment and post removal */
    if(remove_post_button.length > 0) {
        for(i=0; i<remove_post_button.length; i++) {
            let button = remove_post_button[i];
            let pid = button.id.split("_")[1];
            let creator = button.id.split("_")[2];
            button.addEventListener("click", function() { removePost(pid, creator); });
        }
    }
    if(remove_comment_button.length > 0) {
        for(i=0; i<remove_comment_button.length; i++) {
            let button = remove_comment_button[i];
            let pid = button.id.split("_")[1];
            let creator = button.id.split("_")[2];
            let cid = button.id.split("_")[3];
            button.addEventListener("click", function() { removeComment(pid, creator, cid); });
        }
    }
   
    /* Handling comment showcase */
    if(show_comments_buttons.length > 0) {
        for(i=0; i<show_comments_buttons.length; i++) {
            let button = show_comments_buttons[i];
            let list = comment_lists[i];
            list.style.display = "none";
            button.addEventListener("click", function() {
                toggle(list);
                if (list.style.display == "block") {
                    button.innerText = "Nascondi commenti";
                } else {
                    button.innerText = "Mostra commenti";
                }
            });
        }
    }
    /* Handling comment addition */
    if(comment_post_button.length + respond_comment_button.length > 0) {
        const comment_reset = document.getElementsByClassName("comment_reset");
        for(i=0; i<comment_reset.length; i++) {
            let form = comment_reset[i].closest("form");
            form.style.display = "none";
            comment_reset[i].addEventListener("click", function() { toggle(form); });
        }
        if(comment_post_button.length > 0) {
            for(i=0; i<comment_post_button.length; i++) {
                let button = comment_post_button[i];
                let add_comment = document.getElementById("add_" + button.id);
                button.addEventListener("click", function() { mostraFormCommenti(add_comment, null); });
            }
        }
        if(respond_comment_button.length > 0) {
            for(i=0; i<respond_comment_button.length; i++) {
                let button = respond_comment_button[i];
                let risposta = button.innerText.slice(11);
                add_comment = document.getElementById("add_" + button.id.split("-")[0]);
                button.addEventListener("click", function() { mostraFormCommenti(add_comment, risposta); });
            }
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

function addFollow(username) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addFollow.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(owner);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Follow aggiunto con successo al server');
            notify(username, 0, "è diventato un tuo follower");
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'aggiunta del follow:", xhr.status);
        }
    };
    xhr.send();
}

function addBlock(username) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addBlock.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(owner);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Blocco aggiunto con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'aggiunta del blocco:", xhr.status);
        }
    };
    xhr.send();
}

function removeBlock(username, other) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeBlock.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(other);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Blocco rimosso con successo dal server');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante la rimozione del blocco:', xhr.status);
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

function removeFriendRequest(username) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/removeFriendRequest.php?username=' + encodeURIComponent(username) + '&other=' + encodeURIComponent(owner);
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
            console.error("Errore durante la rimozione dell'amico:", xhr.status);
        }
    };
    xhr.send();
}

function notify(username, request, text) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/notify.php?username=' + encodeURIComponent(username) + '&receiver=' + encodeURIComponent(owner) + '&request=' + encodeURIComponent(request) + '&text=' + encodeURIComponent(text);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Notifica inviata');
            location.reload();
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error("Errore durante l'invio della notifica:", xhr.status);
        }
    };
    xhr.send();
}

function removePost(pid, creator) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removePost.php?pid=' + encodeURIComponent(pid) + '&creator=' + encodeURIComponent(creator);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Post rimosso con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione post:', xhr.status);
        }
    };
    xhr.send();
}

function removeComment(pid, creator, cid) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeComment.php?pid=' + encodeURIComponent(pid) + '&creator=' + encodeURIComponent(creator) + '&cid=' + encodeURIComponent(cid);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Commento rimosso con successo dal server');
            location.reload();
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

function mostraFormCommenti(add_comment, risposta) {
    toggle(add_comment);
    username = add_comment.querySelector(".post_author").value;
    request = add_comment.querySelector(".post_number").value;
    if(risposta) {
        add_comment.querySelector("textarea").value = "@" + risposta;
        add_comment.addEventListener("submit", function() { notify(username, request, "ha aggiunto un commento al tuo post"); });
    } else {
        add_comment.querySelector("textarea").placeholder = "Commento al post di " + add_comment.querySelector("input").value;
        add_comment.addEventListener("submit", function() { notify(username, request, "ha aggiunto una risposta al tuo commento"); });
    }
}

function decorateLike(like) {
    let active_like = document.getElementById(like);
    active_like.innerHTML += 'd';
    active_like.style.color = "cyan";
}

function decorateDislike(dislike) {
    let active_dislike = document.getElementById(dislike);
    active_dislike.innerHTML += 'd';
    active_dislike.style.color = "magenta";
}

function addLikeOrDislike(author, post_id, creator, comment_id, type) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/addLikeOrDislike.php?author=' + encodeURIComponent(author) + '&post_id=' + encodeURIComponent(post_id) +
    '&creator=' + encodeURIComponent(creator) + '&comment_id=' + encodeURIComponent(comment_id) + '&type=' + encodeURIComponent(type);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Reazione aggiunta con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante aggiunta reazione:', xhr.status);
        }
    };
    xhr.send();
}

function removeLikeOrDislike(author, post_id, creator, comment_id) {
    let xhr = new XMLHttpRequest();
    let url = 'functions/removeLikeOrDislike.php?author=' + encodeURIComponent(author) + '&post_id=' + encodeURIComponent(post_id) +
    '&creator=' + encodeURIComponent(creator) + '&comment_id=' + encodeURIComponent(comment_id);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Reazione rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione reazione:', xhr.status);
        }
    };
    xhr.send();
}

function like(author, post_id, creator, comment_id) {
    let like_button = document.getElementById('like_button_' + post_id + '_' + creator + '_' + comment_id);
    let like_number = document.getElementById('like_number_' + post_id + '_' + creator + '_' + comment_id);
    if(like_button.textContent.charAt(4)!='d') {
        if(document.getElementById('dislike_button_' + post_id + '_' + creator + '_' + comment_id).textContent.charAt(7)=='d') {
            dislike(author, post_id, creator, comment_id);
        }
        addLikeOrDislike(author, post_id, creator, comment_id, true);
        decorateLike('like_button_' + post_id + '_' + creator + '_' + comment_id);
        notify(creator, post_id, "ha aggiunto un like al tuo post");
        like_number.innerText = parseInt(like_number.innerText) + 1;
    } else {
        removeLikeOrDislike(author, post_id, creator, comment_id);
        like_button.innerHTML = "Like"
        like_button.style.color = "black";
        like_number.innerText = parseInt(like_number.innerText) - 1;
    }
}

function dislike(author, post_id, creator, comment_id) {
    let dislike_button = document.getElementById('dislike_button_' + post_id + '_' + creator + '_' + comment_id);
    let dislike_number = document.getElementById('dislike_number_' + post_id + '_' + creator + '_' + comment_id);
    if(dislike_button.textContent.charAt(7)!='d') {
        if(document.getElementById('like_button_' + post_id + '_' + creator + '_' + comment_id).textContent.charAt(4)=='d') {
            like(author, post_id, creator, comment_id);
        }
        addLikeOrDislike(author, post_id, creator, comment_id, false);
        decorateDislike('dislike_button_' + post_id + '_' + creator + '_' + comment_id);
        dislike_number.innerText = parseInt(dislike_number.innerText) + 1;
    } else {
        removeLikeOrDislike(author, post_id, creator, comment_id);
        dislike_button.innerHTML = "Dislike"
        dislike_button.style.color = "black";
        dislike_number.innerText = parseInt(dislike_number.innerText) - 1;
    }
}