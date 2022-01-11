class Utilities {
    static SetSession(primaryKey, username, password) {
        sessionStorage.setItem("session-primaryKey", primaryKey);
        sessionStorage.setItem("session-username", username);
        sessionStorage.setItem("session-password", password);
    }

    static GetSession() {
        let session_primaryKey = sessionStorage.getItem("session-primaryKey");
        let session_username = sessionStorage.getItem("session-username");
        let session_password = sessionStorage.getItem("session-password");
        return {primaryKey: session_primaryKey, username: session_username, password: session_password};
    }

    static ReindirizzaTo(newURL) {
        window.location.href = newURL;
    }

    static RemoveAllChildren(element) {
        while (element.firstChild) element.removeChild(element.lastChild);
    }

    static FetchToPromise(fetchURL, fetchBody, finallyFunction = null, errorFunction = null, errorContainer = null) {
        errorContainer = (errorContainer) ? errorContainer :  document.querySelector(".alert-defaultContainer");

        return new Promise(resolve => {
            fetch(fetchURL, { method: "POST", body: fetchBody})
            .then(response => response.text())
            .then(text => {
                let toReturn = JSON.parse(text);
                resolve(toReturn);
            })
            .catch(error => {
                if(errorFunction) errorFunction.call();
                else new Alert("error",  "Errore di connessione!", true, errorContainer);
            })
            .finally(function() {
                if(finallyFunction) finallyFunction.call();
            });

        });
    }

    static InsertRecord(fetchURL, fetchBody, errorFunction = null, errorContainer = null) {
        errorContainer = (errorContainer) ? errorContainer :  document.querySelector(".alert-defaultContainer");   
        return new Promise(resolve => {
            fetch(fetchURL, { method: "POST", body: fetchBody})
            .then(response => response.text())
            .then(text => {
                resolve(JSON.parse(text));
            })
            .catch(error => {
                if(errorFunction) errorFunction.call();
                else new Alert("error",  "Errore di connessione!", true, errorContainer);
            });

        });
    }

    static GetRecordFromPrimaryKey(tableName, primaryKey, errorContainer = null) {
        errorContainer = (errorContainer) ? errorContainer :  document.querySelector(".alert-defaultContainer");   
        const fetchURL = "../PHP_QUERIES/phpFunctions-getOne.php?tableName=" + tableName; 
        let toSend = new FormData();

        toSend.append("primaryKey", primaryKey);
        
        return new Promise(resolve => {
            fetch(fetchURL, { method: "POST", body: toSend})
            .then(response => response.text())
            .then(text => {
                resolve(JSON.parse(text));
            })
            .catch(error => {
                new Alert("error",  "Errore di connessione!", true, errorContainer);
            });

        });
    }

    static ModifyRecord(tableName, oldPK, fields, alertContainer = null) {
        alertContainer = (alertContainer) ? alertContainer :  document.querySelector(".alert-defaultContainer");
        const fetchURL = "../PHP_QUERIES/phpFunctions-Modify.php?tableName=" + tableName;

        if(fields.length < 1) {
            new Alert("warning", "Nessun campo è stato modificato!", true, alertContainer);
            return;
        }

        let session = Utilities.GetSession();
            
        fields.push({
            name: "adminUsername",
            value: session["username"]
        }, {
            name: "adminPassword",
            value: session["password"]
        });

        let toSend = new FormData();
        toSend.append("originalPK", oldPK);
        fields.forEach(field => {
            toSend.append(field.name, field.value); 
        });

        let toReturn = false;
        return new Promise(resolve => {
            fetch(fetchURL, { method: "POST", body: toSend})
            .then(response => response.text())
            .then( text => {
                let response = JSON.parse(text);
                if(!response["authentication"]) {
                    new Alert("error", "Autenticazione amministratore fallita!", true, alertContainer);
                    resolve(false);
                    return;
                }

                if(response["uniqueField-collision"]) {
                    new Alert("error", "Modifica fallita, collisione con campo univoco", true, alertContainer);
                    resolve(false);
                    return;
                }

                if(response["updated"]) new Alert("success", "Modifica effettuata con successo", true, alertContainer);
                

                resolve(response["updated"]);
            })
            .catch(error => {
                new Alert("error",  "Errore di connessione!", true, alertContainer);
                resolve(false);
            });

        });

    }
}