function do_register(e) {
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
            console.log(response);
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
            console.log(error);
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
        .finally(function() {
            submitter.disabled = false;
        });
}