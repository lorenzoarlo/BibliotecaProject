class Utility {
    static Summon_ErrorAlert(parentElement, message) {
        let blurredBg = document.createElement("div");
        blurredBg.className = "blurred-background";
        
        let alert = document.createElement("div");
        alert.className = "error-alert";

        let alertContent = document.createElement("span");
        alertContent.className = "alert-content";
        alertContent.innerText = message;
        alertContent.title = message;

        
        let btn_closeAlert = document.createElement("span");
        btn_closeAlert.className = "alert-button";
        btn_closeAlert.innerHTML = '&times;';
        btn_closeAlert.addEventListener("click", () => {
            parentElement.removeChild(blurredBg);
        })

        alert.appendChild(alertContent);
        alert.appendChild(btn_closeAlert);
        blurredBg.appendChild(alert);
        parentElement.appendChild(blurredBg);
    }

    static Summon_SuccessAlert(parentElement, message) {
        let blurredBg = document.createElement("div");
        blurredBg.className = "blurred-background";
        
        let alert = document.createElement("div");
        alert.className = "success-alert";

        let alertContent = document.createElement("span");
        alertContent.className = "alert-content";
        alertContent.innerText = message;
        alertContent.title = message;

        
        let btn_closeAlert = document.createElement("span");
        btn_closeAlert.className = "alert-button";
        btn_closeAlert.innerHTML = '&times;';
        btn_closeAlert.addEventListener("click", () => {
            parentElement.removeChild(blurredBg);
        })

        alert.appendChild(alertContent);
        alert.appendChild(btn_closeAlert);
        blurredBg.appendChild(alert);
        parentElement.appendChild(blurredBg);
    }

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
                    Utility.ReindirizzaTo(newURL);
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