// ----- MUST HAVE ATTRIBUTEs
// -> title = ?""
// -> textbox-placeholder = ?""
// -> query-path = ?""
// -> type = "show", "select"
// -> destinationURL = ?""
// -> id = ?""

// ----- REQUIREMENTS
// ->  'TextInput.js'
// ->  'https://fonts.googleapis.com/css?family=Material+Icons'

// ----- CHILDRENS
// -> <database-column column-coding-name="nomeColonna" not-visible primary-key>NOME DELLA COLONNA</word>
// -> column-coding-name = ?""
// -> not-visible = 
// -> primary-key =

class DatabaseTable extends HTMLElement {

    static RECORDS_SHOWN = 5;

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
        
        this.btn_previousPage = document.createElement("button");
        this.btn_previousPage.className = "navigator-button";
        this.btn_previousPage.disabled = true;
        this.btn_previousPage.innerHTML = "<i class='material-icons'>&#xe314;</i>"
        this.tableNavigator.appendChild(this.btn_previousPage);
        
        this.pageResults = document.createElement("span");
        this.pageResults.className = "result-pages";
        this.pageResults.innerText = "Risultati 0-0 di 0";
        this.tableNavigator.appendChild(this.pageResults);

        this.btn_nextPage = document.createElement("button");
        this.btn_nextPage.className = "navigator-button";
        this.btn_nextPage.disabled = true;
        this.btn_nextPage.innerHTML = "<i class='material-icons'>&#xe315;</i>"
        this.tableNavigator.appendChild(this.btn_nextPage);
        
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
        this.offset = 0;
        this.allRecords_queryPath = "../PHP_QUERIES/phpFunctions-getAll.php?tableName=";
        this.oneRecord_queryPath = "../PHP_QUERIES/phpFunctions-getOne.php?tableName=";
    }

    connectedCallback() {
        Array.from(this.children).filter(el => el.tagName == "DATABASE-COLUMN").forEach(x => {
            this.columns.push({
                isCheckbox: false,
                columnTitle: x.innerText,
                columnName: x.getAttribute("column-coding-name"),
                invisible: x.hasAttribute("not-visible"),
                primaryKey: x.hasAttribute("primary-key")
            });
        });

        // Default attributes value
        if (!this.hasAttribute("title")) this.setAttribute("title", "");
        if (!this.hasAttribute("textbox-placeholder")) this.setAttribute("textbox-placeholder", "");        
        if (!this.hasAttribute("type")) this.setAttribute("type", "show");
        

        this.spanTitle.innerText = this.getAttribute("title");

        this.txtSearch.placeholder = this.getAttribute("textbox-placeholder");

        if(this.getAttribute("type") == "select") {
            this.columns.unshift({
                isCheckbox: true,
                invisible: false,
                columnTitle: null,
                columnName: null,
                primaryKey: false
            });
        }


        // Fill thead
        let headerRow = document.createElement("tr");
        this.columns.forEach(c => {
            let td = document.createElement("td");
            if(c.isCheckbox) td.className = "checkbox-cell";

            if(!c.isCheckbox){
                td.innerText = c.columnTitle;
                if(c.invisible) td.classList.add("invisible-field");
            }
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

        
        if (this.hasAttribute("table-name")) {
            this.allRecords_queryPath += this.getAttribute("table-name");
            this.oneRecord_queryPath += this.getAttribute("table-name");
            this.InitializeValues();
        }
        
    }

    async InitializeValues() {
        let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, "", this.offset, DatabaseTable.RECORDS_SHOWN);
        this.show_updateTable(response);
    }

    show_updateTable(response) {
        this.show_updateTableNavigator("", this.offset, DatabaseTable.RECORDS_SHOWN, response["data"].length, response["totalRows"]);
        this.updateTableRecords(response["data"]);
    }

    updateTableRecords(data) {
        Utilities.RemoveAllChildren(this.tbody);
        
        if(data.length == 0) {
            let trow = document.createElement("tr");
            let td = document.createElement("td");
            td.innerText = "Nessun risultato trovato";
            td.colSpan = this.columns.filter(c => !c.invisible).length;;
            trow.appendChild(td);
            this.tbody.appendChild(trow);
        } else {
            data.forEach(record => {
                let trow = document.createElement("tr");

                this.columns.forEach(column => {
                    let td = document.createElement("td");

                    if(column.isCheckbox) DatabaseTable.appendRadioButton(td, this.id, record[this.columns.find(c => c.primaryKey).columnName]);
                    
                    if(!column.isCheckbox) {
                        td.innerText = record[column.columnName];
                        if(column.primaryKey) td.classList.add("primary-key");
                        if(column.invisible) td.classList.add("invisible-field");
                    }

                    trow.appendChild(td);
                });

                if(this.getAttribute("type") == "show") {
                    trow.onclick = () => {
                        let keyName = this.columns.find(c => c.primaryKey)["columnName"];
                        let keyValue = trow.querySelector(".primary-key").innerText;
                        let URL = `${this.getAttribute("destinationURL")}?${keyName}=${keyValue}`;
                        Utilities.ReindirizzaTo(URL);
                    };
                }

                if(this.getAttribute("type") == "select") {
                    trow.onclick = () => trow.querySelector(".radioButton").checked = true; 
                }
                
                this.tbody.appendChild(trow);
            });
        }
    }

    show_updateTableNavigator(searchString, offset, toRetrieve, retrievedRecords, totalRows) {
        let lastRecordShown = offset + retrievedRecords;
        let offsetToDisplay = (retrievedRecords == 0) ? 0 : offset + 1;

        this.pageResults.innerText = `Risultati ${offsetToDisplay} - ${lastRecordShown} di ${totalRows}`;
        this.btn_previousPage.disabled = (offset == 0);
        this.btn_nextPage.disabled = (lastRecordShown == totalRows);

        this.btn_previousPage.onclick = async () => {
            this.offset -= toRetrieve;
            this.offset = (this.offset < 0) ? 0 : this.offset;
            let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, searchString, this.offset, toRetrieve);
            this.show_updateTable(response);
        }

        this.btn_nextPage.onclick = async () => {
            this.offset += toRetrieve;
            let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, searchString, this.offset, toRetrieve);
            this.show_updateTable(response);
        } 
    }

    select_updateTableNavigator(retrievedRecords) {
        this.pageResults.innerText = `Risultati ${retrievedRecords} - ${retrievedRecords} di ${retrievedRecords}`;
        this.btn_previousPage.disabled = true;
        this.btn_nextPage.disabled = true;   
    }

    static getRecordsBySearchString(path, searchString, offset, retrieveRecords) {
        return new Promise(resolve => {
            let toSend = new FormData();
            
            toSend.append("searchString", searchString);
            toSend.append("offset", offset);
            toSend.append("retrieveRecords", retrieveRecords);
            
            fetch(path, { method: "POST", body: toSend })
            .then((response) => response.text())
            .then((text) => {
                resolve(JSON.parse(text));
            });
        });
    }

    static getRecordByPrimaryKey(path, primaryKey) {
        return new Promise(resolve => {
            let toSend = new FormData();
            
            toSend.append("primaryKey", primaryKey);
            
            fetch(path, { method: "POST", body: toSend })
            .then((response) => response.text())
            .then((text) => {
                resolve(JSON.parse(text));
            })
        });
    }

    static appendRadioButton(cell, groupName, valuePK) {
        cell.className = "checkbox-cell";
        
        let radioButton = document.createElement("input");
        radioButton.type = "radio";
        radioButton.name = groupName;
        radioButton.value = valuePK;
        radioButton.className = "radioButton"
        cell.appendChild(radioButton);

        let rdbLabel = document.createElement("label");
        rdbLabel.className = "radioButton-label";
        cell.appendChild(rdbLabel);
    }

    get value() {
        let toReturn = undefined;
        if(this.getAttribute("type") == "select") {
            let checkedRow = Array.from(this.tbody.querySelectorAll("tr")).find(row => row.querySelector(".radioButton").checked);
            if (checkedRow) toReturn = checkedRow.querySelector(".primary-key").innerText;
        }
        return toReturn;
    }

    async setNewValue(newValue) {
        let data = [];
        let response = await DatabaseTable.getRecordByPrimaryKey(this.oneRecord_queryPath, newValue);
        if (response["record"]) data.push(response["record"]);
        this.updateTableRecords(data);
        this.select_updateTableNavigator(data.length);
    }

    set value(newValue) {
        console.log("Impossibile impostare il valore. Utilizzare la funzione setNewValue(newValue)...");
    }

}

document.currentScript.onload = () => customElements.define("database-table", DatabaseTable);