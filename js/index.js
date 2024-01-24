function selectPost() {
    const select_form = document.getElementById("select_form");

    select_form.addEventListener("submit", (event) => {
        event.preventDefault();
        fetch(select_form.action, {
            method: 'POST',
            body: new URLSearchParams(new FormData(select_form))
        }).then((response) => {
            document.getElementById("post_list").innerHTML = "<?php $post_list = " + response[0] + ";  require_once 'post_list.php' ?>";
            document.querySelectorAll("[id$=_comment_list]").hidden = true;
            decorate(response[1], response[2]);
        })
        .catch(err => console.error('Errore durante estrazione post:', err));
    })
}

function removePost(pid) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/home/removePost.php', true);
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
    let data = encodeURI('pid=' + encodeURIComponent(pid));
    xhr.send(data);
}

function removeComment(cid) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/home/removeComment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    comment_to_remove = document.getElementById('comment' + cid);
    if(isset(comment_to_remove)) {
        comment_to_remove.parentElement.removeChild(comment_to_remove);
    }
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
            console.log('Commento rimosso con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione commento:', xhr.status);
        }
    };
    let data = encodeURI('cid=' + encodeURIComponent(cid));
    xhr.send(data);
}

function addLikeOrDislike(element_id, type) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/home/addLikeOrDislike.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Reazione aggiunta con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante aggiunta reazione:', xhr.status);
        }
    };
    let data = encodeURI('element_id=' + encodeURIComponent(element_id), '&type=' + encodeURIComponent(type));
    xhr.send(data);
}

function removeLikeOrDislike(element_id) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/home/removeLikeOrDislike.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log('Reazione rimossa con successo dal server');
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Errore durante rimozione reazione:', xhr.status);
        }
    };
    let data = encodeURI('element_id=' + encodeURIComponent(element_id));
    xhr.send(data);
}

function mostraFormPost() {
    const add_post = document.getElementsByName('add_post_form');
    if (add_post.hidden == true) {
        add_post.hidden = false;
    } else {
        add_post.hidden = true;
    }
}

function mostraFormCommenti(post_id, post_utente, post_data, risposta) {
    const post_id_input = document.getElementById('post_id_input');
    const add_comment = document.getElementsByName('add_comment_form');
    const comment_text = document.getElementById('comment_text');
    const comment_post_info = document.getElementById('comment_post_info');
    if (add_comment.hidden == true) {
        add_comment.hidden = false;
    } else if (post_id_input.innerHTML == post_id) {
        add_comment.hidden = true;
    }
    post_id_input.innerHTML = post_id;
    comment_text.innerHTML = risposta;
    comment_post_info.innerHTML = "Commento al post di " + post_utente + ", " + post_data;
}

function mostraCommentiPost(post_id) {
    let comment_list = document.getElementById(post_id + '_comment_list')
    if (comment_list.hidden == true) {
        comment_list.hidden = false;
    } else {
        comment_list.hidden = true;
    }
}

function like(element_id) {
    let like_button = document.getElementById(element_id + '_like_button');
    if(like_button.textContent.charAt(4)!='d') {
        if(document.getElementById(element_id + '_dislike_button').textContent.charAt(7)=='d') {
            dislike(element_id);
        }
        addLikeOrDislike(element_id, true);
        like_button.innerHTML += 'd';
        like_button.style.color = "cyan";
    } else {
        removeLikeOrDislike(element_id);
        like_button.innerHTML = "Like"
        like_button.style.color = "black";
    }
}

function dislike(element_id) {
    let dislike_button = document.getElementById(element_id + '_dislike_button');
    if(dislike_button.textContent.charAt(7)!='d') {
        if(document.getElementById(element_id + '_like_button').textContent.charAt(4)=='d') {
            like(element_id);
        }
        addLikeOrDislike(element_id, false);
        dislike_button.innerHTML += 'd';
        dislike_button.style.color = "magenta";
    } else {
        removeLikeOrDislike(element_id);
        dislike_button.innerHTML = "Dislike"
        dislike_button.style.color = "black";
    }
}

function decorate(element_id_like, element_id_dislike) {
    element_id_like.foreach((element) => {
        let active_like = document.getElementById(element + '_like_button');
        active_like.innerHTML += 'd';
        active_like.style.color = "cyan";
    })
    element_id_dislike.foreach((element) => {
        let active_like = document.getElementById(element + '_dislike_button');
        active_like.innerHTML += 'd';
        active_like.style.color = "magenta";
    })
}

function hide() {
    document.getElementsByTagName("form").hidden = true;
}