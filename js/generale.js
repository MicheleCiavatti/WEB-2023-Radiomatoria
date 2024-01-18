function cookiesSet() {
    return !isempty(document.cookie);
}

function readCookie(cookieName) {
    let cookies = document.cookie;
    let cookieArray = cookies.split("; ");
 
    for (let i = 0; i < cookieArray.length; i++) {
       let cookie = cookieArray[i];
       let [name, value] = cookie.split("=");
      
       if (name === cookieName) {
          return decodeURIComponent(value);
       }
    }
    
    return null;
}

function accessProfile(username) {
    window.location.href = "profile.php";
    document.getElementsByTagName("body").onload("<?php profileAccess(" + username + "); ?>");
}
