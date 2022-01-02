class Utilities {
    static SetSession(username, password) {
        sessionStorage.setItem("session-username", username);
        sessionStorage.setItem("session-password", password);
    }

    static GetSession() {
        let session_username = sessionStorage.getItem("session-username");
        let session_password = sessionStorage.getItem("session-password");
        return {username: session_username, password: session_password};
    }

    static do_login(e) {
        let submitter = e.srcElement;
    
        let toSend = new FormData();
        let username = document.querySelector("#txtUsername").value;
        let password = document.querySelector("#txtPassword").value;
        toSend.append("username", username);
        toSend.append("password", password);
    
        submitter.disabled = true;
    
        fetch("../PHP_QUERIES/phpFunction-Login.php", { method: "POST", body: toSend })
            .then((response) => response.text())
            .then(function (response) {
                let output = JSON.parse(response);
                if (output["authentication"]) {
                    let newURL = (output["user-type"] == "admin") ? "admin_personalArea.html" : "user_personalArea.html";
                    Utilities.SetSession(username, password);
                    Utilities.ReindirizzaTo(newURL);
                } else {
                    new Alert("error",  
                    "Errore di autenticazione! Credenziali non corrette!",
                    true,
                    document.querySelector(".loginForm-container"));
                }
            })
            .catch(error => {
                new Alert("error",  
                    "Errore di connessione!",
                    true,
                    document.querySelector(".loginForm-container"));  
            })
            .finally(function() {
                submitter.disabled = false;
            });   
    }

    static ReindirizzaTo(newURL) {
        window.location.href = newURL;
    }

    static RemoveAllChildren(element) {
        while (element.firstChild) element.removeChild(element.lastChild);
    }

    static ResetPaginationDatabaseTable(containerID) {
        document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset = 0;
    }
}