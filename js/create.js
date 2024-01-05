function create_result() {
    let create_form = document.getElementByName("new_account_form");

    create_form.addEventListener("submit", (event) => {
        event.preventDefault();
        fetch(create_form.action, {
            method: 'POST',
            body: new URLSearchParams(new FormData(create_form))
        }).then((response) => response.text())
        .then((response) => {
            if(empty(response)) {
                let email = document.getElementById("address");
                let password = document.getElementById("passwd1");
                userLogin(email, password);
            } else {
                document.getElementById("create_fail").innerHTML = response;
            }
        })
        .catch(err => document.getElementById("create_fail").innerHTML = err)
    })
}