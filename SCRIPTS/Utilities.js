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

        toSend.append("primary-key", primaryKey);
        
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

    static ModifyRecord()
}