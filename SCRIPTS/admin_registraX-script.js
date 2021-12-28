function do_register_amministratore(e) {
    e.preventDefault();
    let submitter = e.srcElement;

    let toSend = new FormData();

    let session_data = Utility.GetSession();

    let mail = document.querySelector("#txtMail").value;

    toSend.append("admin_username", session_data.username);
    toSend.append("admin_password", session_data.password);
    toSend.append("newAdmin_mail", mail);

    submitter.disabled = true;

    let alertParentElement = document.querySelector(".registerForm-container");

    fetch("../PHP_QUERIES/doRegisterAdmin.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);
            if (!output["authentication"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Amministratore non identificato!");  
                return;
            }
            if (!output["newMail"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "L'email inserita è già stata utilizzata!");  
                return;
            }
            Utility.Summon_SuccessAlert(alertParentElement, "Nuovo amministratore creato con successo!");
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");
        })
        .finally(function() {
            submitter.disabled = false;
        });
}

function do_register_user(e) {
    e.preventDefault();
    let submitter = e.srcElement;

    let toSend = new FormData();

    let session_data = Utility.GetSession();

    let name = document.querySelector("#txtName").value;
    let surname = document.querySelector("#txtSurname").value;
    let mail = document.querySelector("#txtMail").value;
    let codiceFiscale = document.querySelector("#txtCodiceFiscale").value;

    toSend.append("admin_username", session_data.username);
    toSend.append("admin_password", session_data.password);
    toSend.append("newUser_name", name);
    toSend.append("newUser_surname", surname);
    toSend.append("newUser_mail", mail);
    toSend.append("newUser_codiceFiscale", codiceFiscale);

    submitter.disabled = true;

    let alertParentElement = document.querySelector(".registerForm-container");

    fetch("../PHP_QUERIES/doRegisterUser.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);
            if (!output["authentication"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Amministratore non identificato!");  
                return;
            }
            if (!output["newMail"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "L'email inserita è già stata utilizzata!");  
                return;
            }
            if (!output["newCF"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Il codice fiscale inserito è già stata utilizzato!");  
                return;
            }
            Utility.Summon_SuccessAlert(alertParentElement, "Utente creato con successo!")
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
        .finally(function() {
            submitter.disabled = false;
        });
}

function do_register_categoria(e) {
    e.preventDefault();
    let submitter = e.srcElement;

    let toSend = new FormData();

    let session_data = Utility.GetSession();

    let codiceCat = document.querySelector("#txtCodiceCat").value;
    let descrizioneCat = document.querySelector("#txtDescrizioneCat").value;

    toSend.append("admin_username", session_data.username);
    toSend.append("admin_password", session_data.password);
    toSend.append("newCat_codice", codiceCat);
    toSend.append("newCat_descrizione", descrizioneCat);

    submitter.disabled = true;

    let alertParentElement = document.querySelector(".registerForm-container");

    fetch("../PHP_QUERIES/doRegisterCategoria.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);
            if (!output["authentication"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Amministratore non identificato!");  
                return;
            }
            if (!output["newCod"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Codice categoria già presente!");  
                return;
            }
            if (!output["newDesc"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Descrizione categoria già presente");  
                return;
            }
            Utility.Summon_SuccessAlert(alertParentElement, "Categoria creata con successo!")
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
        .finally(function() {
            submitter.disabled = false;
        });
}