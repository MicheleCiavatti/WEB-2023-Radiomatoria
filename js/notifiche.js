function count() {
    document.getElementById("notifications_total").innerHTML += document.getElementById("unread_notifications_list").childElementCount + document.getElementById("read_notifications_list").childElementCount;
}

function countRead() {
    document.getElementById("pag_notifiche").firstChild.innerHTML += document.getElementById("unread_notifications_list").childElementCount;
}