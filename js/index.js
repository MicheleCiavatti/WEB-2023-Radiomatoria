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
        addLike(element_id);
        like_button.innerHTML += 'd';
        like_button.style.color = "cyan";
    } else {
        removeLike(element_id);
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
        addDislike(element_id);
        dislike_button.innerHTML += 'd';
        dislike_button.style.color = "magenta";
    } else {
        removeDislike(element_id);
        dislike_button.innerHTML = "Dislike"
        dislike_button.style.color = "black";
    }
}
//hide e decorate vengono chiamate al caricamento della pagina
function decorate(element_id_like, element_id_dislike) {
/* element_id_like è un inner join tra $[templateParams("post")]
ed una query che ritorna una lista di id di post/commenti su cui l'utente registrato ha messo like;
element_id_dislike è un inner join tra $[templateParams("post")]
ed una query che ritorna una lista di id di post/commenti su cui l'utente registrato ha messo dislike */
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
    const forms = document.getElementsByTagName("form");
    const restricted_buttons = document.getElementsByClassName("access_required");
    const preference_buttons = document.getElementsByClassName("preference_button");
    forms.hidden = true;
    if (isset($_SESSION["uid"])) {
        restricted_buttons.hidden = true;
        restricted_buttons.disabled = true;
        preference_buttons.disabled = true;
    }
}