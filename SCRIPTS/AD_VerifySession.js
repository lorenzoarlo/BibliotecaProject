document.currentScript.onload = async () => {
    let session = Utilities.GetSession();

    let toSend = new FormData();
    let loginUsername = session["username"];
    let loginPassword = session["password"];
    toSend.append("loginUsername", loginUsername);
    toSend.append("loginPassword", loginPassword);

    let response = await Utilities.FetchToPromise("../PHP_QUERIES/phpFunction-Login.php", toSend);
    
    const alert_defaultContainer = document.querySelector(".alert-defaultContainer");

    if(response["authentication"] && response["user-type"] == "utente") Utilities.ReindirizzaTo("../PAGES/US_PersonalArea.html");

    if(!response["authentication"]) {
        await new Alert("error",
        "Errore di autenticazione! Credenziali non corrette!",
        true,
        alert_defaultContainer
        );

        Utilities.ReindirizzaTo("../PAGES/landingPage.html");
    }
}