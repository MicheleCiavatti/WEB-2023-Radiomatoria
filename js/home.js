document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.getElementsByClassName('like_button');
    const username = document.getElementById('session_user_name') ? document.getElementById('session_user_name').innerHTML : null;
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

function likePost(post_author, post_number, liker) {
    const xhr = new XMLHttpRequest();
    const url = 'functions/likePost.php?post_author=' + encodeURIComponent(post_author) 
                + '&post_number=' + encodeURIComponent(post_number)
                + '&liker=' + encodeURIComponent(liker);
    xhr.open('GET', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Post liked successfully');
            location.reload();
        } else if (xhr.readyState == 4 && xhr.status != 200) {
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
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Post unliked successfully');
            location.reload();
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            console.error('Error while unliking the post:', xhr.status);
        }
    };
    xhr.send();
}