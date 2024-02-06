const show_comments_buttons = document.getElementsByName("show_comments");
const comment_lists = document.getElementsByName("comment_list");
const remove_post_button = document.getElementsByName("remove_post");
const remove_comment_button = document.getElementsByName("remove_comment");

const comment_post_button = document.getElementsByName("comment_post");
const respond_comment_button = document.getElementsByName("answer_comment");

const username = document.getElementById('session_user_name') ? document.getElementById('session_user_name').innerHTML : null;

document.addEventListener('DOMContentLoaded', function() {
    /* Handling comment and post addition */
    if(username) {
        const add_post = document.getElementById('add_post_form');
        const add_post_button = document.getElementById('add_post_button');
    
        add_post_button.addEventListener("click", function() { toggle(add_post) });
        add_post.style.display = "none";

        /* Likes and dislikes */
        const like_buttons = document.getElementsByName("like_button");
        const dislike_buttons = document.getElementsByName("dislike_button");
        for(i = 0; i < like_buttons.length; i++) {
            let like_button = like_buttons[i];
            if(like_button.className == "preferred_button") {
                decorateLike(like_button.id);
            }
            let element_id = like_button.id.slice(12);
            let post_id = element_id.split("_")[0];
            let creator = element_id.split("_")[1];
            let comment_id = element_id.split("_")[2];
            like_button.addEventListener("click", function() { like(username, post_id, creator, comment_id); });

            let dislike_button = dislike_buttons[i];
            if(dislike_button.className == "preferred_button") {
                decorateDislike(dislike_button.id);
            }
            dislike_button.addEventListener("click", function() { dislike(username, post_id, creator, comment_id); });
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
   
    /* Handling other comment and post buttons */
    if(show_comments_buttons.length > 0) {
        comments = document.getElementsByName("comment_list");
        if(comments.length > 0) {
            for(i=0; i<comments.length; i++) {
                comments[i].style.display = "none";
            }
        }
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
    if(risposta) {
        add_comment.querySelector("textarea").value = "@" + risposta;
    } else {
        add_comment.querySelector("textarea").placeholder = "Commento al post di " + add_comment.querySelector("input").value;
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
        addLikeOrDislike(author, post_id, creator, comment_id, 1);
        decorateLike('like_button_' + post_id + '_' + creator + '_' + comment_id);
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
        addLikeOrDislike(author, post_id, creator, comment_id, 0);
        decorateDislike('dislike_button_' + post_id + '_' + creator + '_' + comment_id);
        dislike_number.innerText = parseInt(dislike_number.innerText) + 1;
    } else {
        removeLikeOrDislike(author, post_id, creator, comment_id);
        dislike_button.innerHTML = "Dislike"
        dislike_button.style.color = "black";
        dislike_number.innerText = parseInt(dislike_number.innerText) - 1;
    }
}