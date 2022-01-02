// ----- REQUIREMENTS ----- 
// -> 'https://fonts.googleapis.com/css?family=Open+Sans'
// -> '../STYLES/Alert-style.css'
class Alert {
    static EXIT_AnimationKeyframe = [ 
        { transform: "scale(1)"  },
        { transform: "scale(0)" } 
    ];

    static EXIT_AnimationKeyframeProperties = {
        duration: 200,
        iterations: 1,
        fill: "forwards"
    }

    constructor(alertType, message, toSummon = false, parent = null) {
        if(alertType !== "success" && alertType !== "error") throw "Alert type doesn't exist!";

        this.blurredBackground = document.createElement("div");
        this.blurredBackground.className = "blurred-background";
        
        this.alertBox = document.createElement("div");
        this.alertBox.className = `alert ${alertType} `;

        let alertContent = document.createElement("span");
        alertContent.className = "alert-content";
        alertContent.innerText = message;
        alertContent.title = message;

        
        this.btn_closeAlert = document.createElement("span");
        this.btn_closeAlert.className = "alert-button";
        this.btn_closeAlert.innerHTML = '&times;';

        this.alertBox.appendChild(alertContent);
        this.alertBox.appendChild(this.btn_closeAlert);
        this.blurredBackground.appendChild(this.alertBox);

        if(toSummon) this.SummonAlert(parent);
        
    }

    SummonAlert(parent) {
        parent.appendChild(this.blurredBackground);
        this.btn_closeAlert.addEventListener("click", () => {

            this.alertBox.animate(Alert.EXIT_AnimationKeyframe, Alert.EXIT_AnimationKeyframeProperties)
            .onfinish = () => parent.removeChild(this.blurredBackground);
            
        })
    }

}