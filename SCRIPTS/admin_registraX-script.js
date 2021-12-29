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

    fetch("../PHP_QUERIES/php-insertAdmin.php", { method: "POST", body: toSend })
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

function do_register_utente(e) {
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

    fetch("../PHP_QUERIES/php-insertUser.php", { method: "POST", body: toSend })
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

    fetch("../PHP_QUERIES/php-insertCategory.php", { method: "POST", body: toSend })
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

function do_register_autore(e) {
    e.preventDefault();
    let submitter = e.srcElement;

    let toSend = new FormData();

    let session_data = Utility.GetSession();

    let nome = document.querySelector("#txtNome").value;
    let cognome = document.querySelector("#txtCognome").value;
    let codAutore = document.querySelector("#txtCodAutore").value;
    let annoNascita = document.querySelector("#txtAnnoNascita").value;
    let biografia = document.querySelector("#txtBiografia").value;

    toSend.append("admin_username", session_data.username);
    toSend.append("admin_password", session_data.password);
    toSend.append("newAutore_nome", nome);
    toSend.append("newAutore_cognome", cognome);
    toSend.append("newAutore_codAutore", codAutore);
    toSend.append("newAutore_annoNascita", annoNascita);
    toSend.append("newAutore_biografia", biografia);

    submitter.disabled = true;

    let alertParentElement = document.querySelector(".registerForm-container");

    fetch("../PHP_QUERIES/php-insertAuthor.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            console.log(response);
            let output = JSON.parse(response);
            if (!output["authentication"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Amministratore non identificato!");  
                return;
            }
            if (!output["newCodAutore"]) {
                Utility.Summon_ErrorAlert(alertParentElement, "Codice autore già presente!");  
                return;
            }
            Utility.Summon_SuccessAlert(alertParentElement, "Autore creato con successo!")
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
        .finally(function() {
            submitter.disabled = false;
        });
}