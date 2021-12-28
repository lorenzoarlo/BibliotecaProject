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

    static changeTxt_visibility(e, inputID) {
        let txt = document.querySelector(`#${inputID}`);
        let tipo = txt.type == "password" ? "text" : "password";
        e.srcElement.classList.toggle("fa-eye-slash");
        txt.setAttribute("type", tipo);
    }

    static waitFor = time => new Promise(resolve => setTimeout(resolve, time));

    static async write_typewriting(element, finalText, typingSpeed, loop = false, delay = 0) {
        return new Promise(async resolve => {
            for(let i = 0; i < finalText.length; i++) {
                element.innerText = finalText.substring(0, i + 1);
                await Utility.waitFor(typingSpeed);
            }
            resolve();
            if(loop) {
                await Utility.waitFor(delay);
                Utility.write_typewriting(element, finalText, typingSpeed, true, delay)
            };
        });
    }

    static async erase_typewriting(element, erasingSpeed) {
        return new Promise(async resolve => {
            let initialLength = element.innerText.length;
            for(let i = 0; i < initialLength; i++) {
                element.innerText = element.innerText.substring(0, element.innerText.length - 1);
                await Utility.waitFor(erasingSpeed);
            }
            resolve();
        });
    }

    static writeWords_typewriting(element, words, typingSpeed, stillTime, erasingSpeed) {
        return new Promise(async resolve => {
            for(let i = 0; i < words.length; i++) {
                await Utility.write_typewriting(element, words[i], typingSpeed);
                await Utility.waitFor(stillTime);
                await Utility.erase_typewriting(element, erasingSpeed);
            }
            resolve();
            Utility.writeWords_typewriting(element, words, typingSpeed, stillTime, erasingSpeed);
        });
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

    static ReindirizzaTo(newURL) {
        window.location.href = newURL;
    }

}