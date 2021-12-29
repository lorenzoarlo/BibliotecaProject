 this.onload = () => {
    const TEXT_TO_ANIMATE = "\"Inserire una citazione commovente ;)\"";
    const TYPING_SPEED = 0.15 * 1000;
    const ERASING_SPEED = 0.1 * 1000;
    const STILL_TIME = 2 * 1000;
    Utility.write_typewriting(document.querySelector("#animated-quote"), TEXT_TO_ANIMATE, TYPING_SPEED, true, 2000)
    Utility.writeWords_typewriting(document.querySelector(".credits-subtitle"), ["Arlotti Lorenzo", "Graziotin Nicola", "Marcatelli Francesco"], TYPING_SPEED, STILL_TIME, ERASING_SPEED); 
}

function do_login(e) {
    e.preventDefault();
    let submitter = e.srcElement;

    let toSend = new FormData();
    let username = document.querySelector("#txtUsername").value;
    let password = document.querySelector("#txtPassword").value;
    toSend.append("username", username);
    toSend.append("password", password);

    submitter.disabled = true;

    fetch("../PHP_QUERIES/php-login.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);
            if (output["authentication"]) {
                let newURL = (output["user-type"] == "admin") ? "admin_personalArea.html" : "user_personalArea.html";
                Utility.SetSession(username, password);
                window.location.href = newURL;
            } else {
                Utility.Summon_ErrorAlert(document.querySelector(".loginForm-container"), "Errore di autenticazione! Credenziali non corrette!");
            }
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(document.querySelector(".loginForm-container"), "Errore di connessione!");  
        })
        .finally(function() {
            submitter.disabled = false;
        });   
}