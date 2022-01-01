// ----- MUST HAVE ATTRIBUTEs
// -> title = ?""
// -> textbox-placeholder = ?""
// -> query-path = ?""

// ----- REQUIREMENTS
// ->  'TextInput.js'
// ->  'https://fonts.googleapis.com/css?family=Material+Icons'

// ----- CHILDRENS
// -> <database-column column-coding-name="nomeColonna" not-visible primary-key>NOME DELLA COLONNA</word>
// -> column-coding-name = ?""
// -> not-visible = 
// -> primary-key =

class DatabaseTable extends HTMLElement {

    constructor() {
        super();

        this.attachShadow({mode: 'open'});

        const stylesheets = ["../STYLES/DatabaseTable-style.css", "https://fonts.googleapis.com/css?family=Material+Icons"];

        stylesheets.forEach(element => {
            let styleElement = document.createElement("link");
            styleElement.href = element;
            styleElement.rel = "stylesheet";
            this.shadowRoot.appendChild(styleElement);

        });
        
        this.parentContainer = document.createElement("div");
        this.parentContainer.className = "table-container";

        this.spanTitle = document.createElement("span");
        this.spanTitle.className = "default-title";
        this.parentContainer.appendChild(this.spanTitle);

        this.txtSearch = document.createElement("text-input");
        this.txtSearch.type = "search";
        this.parentContainer.appendChild(this.txtSearch);
        
        this.tableNavigator = document.createElement("div");
        this.tableNavigator.className = "table-navigator";
        
        this.previousButton = document.createElement("button");
        this.previousButton.className = "navigator-button";
        this.previousButton.disabled = true;
        this.previousButton.innerHTML = "<i class='material-icons'>&#xe314;</i>"
        this.tableNavigator.appendChild(this.previousButton);
        
        this.pageResults = document.createElement("span");
        this.pageResults.className = "result-pages";
        this.pageResults.dataset.offset = "0";
        this.pageResults.innerText = "Risultati 0-0 di 0";
        this.tableNavigator.appendChild(this.pageResults);

        this.nextButton = document.createElement("button");
        this.nextButton.className = "navigator-button";
        this.nextButton.disabled = true;
        this.nextButton.innerHTML = "<i class='material-icons'>&#xe315;</i>"
        this.tableNavigator.appendChild(this.nextButton);
        
        this.parentContainer.appendChild(this.tableNavigator);
        
        this.table = document.createElement("table");
        this.table.className = "database-table";

        this.thead = document.createElement("thead");
        this.table.appendChild(this.thead);


        this.tbody = document.createElement("tbody");
        this.table.appendChild(this.tbody);


        this.parentContainer.appendChild(this.table);

        this.shadowRoot.appendChild(this.parentContainer);

        this.columns = [];

    }

    connectedCallback() {
        Array.from(this.children).filter(el => el.tagName == "DATABASE-COLUMN").forEach(x => {
            this.columns.push({
                columnTitle: x.innerText,
                columnName: x.getAttribute("column-coding-name"),
                invisible: x.hasAttribute("not-visible"),
                primaryKey: x.hasAttribute("primary-key")
            });
        });

        // Default attributes value
        if (!this.hasAttribute("title")) this.setAttribute("title", "");
        if (!this.hasAttribute("textbox-placeholder")) this.setAttribute("textbox-placeholder", "");
        

        this.spanTitle.innerText = this.getAttribute("title");

        this.txtSearch.placeholder = this.getAttribute("textbox-placeholder");

        // Fill thead
        let headerRow = document.createElement("tr");
        this.columns.forEach(c => {
            let td = document.createElement("td");
            td.innerText = c.columnTitle;
            if(c.invisible) td.classList.add("invisible-field");
            headerRow.appendChild(td);
        });
        this.thead.appendChild(headerRow);
        
        // Fill default tbody
        let defaultRow = document.createElement("tr");
        let defaultCell = document.createElement("td");
        defaultCell.innerText = "Errore di connessione";
        defaultCell.colSpan = this.columns.filter(c => !c.invisible).length;
        defaultRow.appendChild(defaultCell);
        this.tbody.appendChild(defaultRow); 



    }
}

document.currentScript.onload = () => customElements.define("database-table", DatabaseTable);