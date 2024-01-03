function count() {
    document.getElementById("notifications_total").innerHTML += document.getElementById("notifications_list").childElementCount;
}

function hide() {
    document.getElementsByName("friend_accept").hidden = true;
    document.getElementsByClassName("req_ami_button").hidden = false;
}

function write() {
    document.getElementsByClassName("req_ami_text").innerHTML = "ti ha inviato una richiesta di amicizia";
    document.getElementsByClassName("ref_ami_text").innerHTML = "ha rifiutato la tua richiesta di amicizia";
}