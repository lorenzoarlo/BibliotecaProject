// ----- MUST HAVE ATTRIBUTEs
// -> type = "text", "password", "iconButton"
// -> placeholder = ?""
// -> id = ?""
// -> main-color = colore
// -> secondary-color = color
// -> readonly
// -> icon-classes = ?""


// ----- REQUIREMENTS
// ->  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css'
// ->  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
// ->  'https://fonts.googleapis.com/css?family=Poppins'

class TextInput extends HTMLElement {

    static DEFAULT_MAIN_COLOR = "#004200";
    static DEFAULT_SECONDARY_COLOR = "#22dc5d";


    constructor() {
        super();

        this.attachShadow({mode: 'open'});

        const stylesheets = ["../STYLES/TextInput-style.css", 
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css", 
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", 
        "https://fonts.googleapis.com/css?family=Material+Icons"];

        stylesheets.forEach(element => {
            let styleElement = document.createElement("link");
            styleElement.href = element;
            styleElement.rel = "stylesheet";
            this.shadowRoot.appendChild(styleElement);

        });
        
        this.parentContainer = document.createElement("div");
        this.parentContainer.className = "txtInput-container";

        this.txtInput = document.createElement("input");
        this.txtInput.className = "txtInput";
        this.txtInput.placeholder = ".";
        this.txtInput.autocomplete = "off";
        
        this.parentContainer.appendChild(this.txtInput);
        
        this.label = document.createElement("label");
        this.label.className = "txtInput-label";
        this.parentContainer.appendChild(this.label);

        this.shadowRoot.appendChild(this.parentContainer);
    }

    connectedCallback() {
        // Default attributes value
        if (!this.hasAttribute("type")) this.setAttribute("type", "text");
        if (!this.hasAttribute("main-color")) this.setAttribute("main-color", TextInput.DEFAULT_MAIN_COLOR);
        if (!this.hasAttribute("secondary-color")) this.setAttribute("secondary-color", TextInput.DEFAULT_SECONDARY_COLOR);
        if (!this.hasAttribute("placeholder")) this.setAttribute("placeholder", "");
        if (!this.hasAttribute("icon-classes")) this.setAttribute("icon-classes", "fa fa-search")

        if(this.hasAttribute("read-only")) {
            this.txtInput.classList.add("inside-lock");
            let lockIcon = document.createElement("i");
            lockIcon.className = "fa fa-lock inside-icon-lock";
            this.parentContainer.appendChild(lockIcon);
            this.txtInput.readOnly = true;
        }

        this.style.setProperty("--main-color", this.getAttribute("main-color"));
        this.style.setProperty("--secondary-color", this.getAttribute("secondary-color"));


        this.updateType();

        this.label.innerText = this.getAttribute("placeholder");
    }

    updateType() {
        this.txtInput.setAttribute("type", (this.getAttribute("type") == "password") ? "password" : "text");

        if(this.getAttribute("type") == "password" || this.getAttribute("type") == "iconButton") {
            this.txtInput.classList.add("inside-icon");
            if(this.parentContainer.contains(this.icon)) this.parentContainer.removeChild(this.icon);

            this.icon = document.createElement("i");
            this.icon.classList.add("inside-icon-toggle");


            if(this.getAttribute("type") == "iconButton") {
                this.icon.className += " " + this.getAttribute("icon-classes");    
            }

            if(this.getAttribute("type") == "password") {
                this.icon.className += " " + "far fa-eye"
                this.icon.addEventListener("click", () => {
                    this.txtInput.setAttribute("type", this.txtInput.type == "password" ? "text" : "password");
                    this.icon.classList.toggle("fa-eye-slash");
                });    
            }

            this.parentContainer.appendChild(this.icon);

        }
    }

    onIconButtonTextInputIcon_click(event) {
        if(this.type == "iconButton") this.icon.onclick = event;
    }

    onTextInput_blur(event) {
        this.txtInput.onblur = event;
    }

    onTextInput_focus(event) {
        this.txtInput.onfocus = event;
    }
    


    // ----- GET & SET -----
    get value() {
        return this.txtInput.value;
    }
      
    set value(newValue) {
        newValue = (newValue) ? newValue : "";
        this.txtInput.value = newValue;
    }

    get readonly() {
        return this.hasAttribute("read-only");
    }

    set readonly(newValue) {
        if(!this.readonly && newValue) {
            this.txtInput.classList.add("inside-lock");
            let lockIcon = document.createElement("i");
            lockIcon.className = "fa fa-lock inside-icon-lock"; 
            this.parentContainer.appendChild(lockIcon);
        }
        
        if(this.readonly && !newValue) {
            this.txtInput.classList.remove("inside-lock");
            this.parentContainer.removeChild(this.shadowRoot.querySelector(".inside-icon-lock"));   
            
        }

        if(newValue) this.setAttribute("read-only", "");
        else this.removeAttribute("read-only");

        this.txtInput.readOnly = newValue;

    }

    get iconClasses() {
        return this.getAttribute("icon-classes");
    }

    set iconClasses(newValue) {
        this.setAttribute("icon-classes", newValue);
        this.updateType();
    }

    get placeholder() {
        return this.getAttribute("placeholder");
    }

    set placeholder(newValue) {
        this.setAttribute("placeholder", newValue);
        this.label.innerText = this.getAttribute("placeholder");
    }

    get type() {
        return this.getAttribute("type");
    }

    set type(newValue) {
        this.setAttribute("type", newValue);
        this.updateType();
    }

    


}

document.currentScript.onload = () => customElements.define("text-input", TextInput);
