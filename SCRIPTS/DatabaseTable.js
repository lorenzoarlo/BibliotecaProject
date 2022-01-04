// ----- MUST HAVE ATTRIBUTEs
// -> table-name = ?""
// -> textbox-placeholder = ?""
// -> type = "show", "select"
// -> destinationURL = ?""
// -> id = ?""
// -> hidden = 
// -> read-only

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

    static SELECT_TXTINPUT_ICON = "fa fa-refresh";

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
        
        this.txtSearch = document.createElement("text-input");
        this.txtSearch.type = "iconButton";
        this.parentContainer.appendChild(this.txtSearch);

        this.mantelloContainer = document.createElement("div");
        this.mantelloContainer.className = "mantello";

        this.hideAndShow = document.createElement("button");
        this.hideAndShow.className = "hideAndShow";
        this.hideAndShow.innerText = "-";
        this.hideAndShow.onclick = () => this.makeTableVisible(); 
        this.parentContainer.appendChild(this.hideAndShow);

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
        
        this.mantelloContainer.appendChild(this.tableNavigator);
        
        this.table = document.createElement("table");
        this.table.className = "database-table";

        this.thead = document.createElement("thead");
        this.table.appendChild(this.thead);

        this.tbody = document.createElement("tbody");
        this.table.appendChild(this.tbody);

        this.mantelloContainer.appendChild(this.table);
        this.parentContainer.appendChild(this.mantelloContainer);

        this.shadowRoot.appendChild(this.parentContainer);

        this.columns = [];
        this.offset = 0;
        this.allRecords_queryPath = "../PHP_QUERIES/phpFunctions-getAll.php?tableName=";
        this.oneRecord_queryPath = "../PHP_QUERIES/phpFunctions-getOne.php?tableName=";
        this.recordChecked = undefined;
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
        if (!this.hasAttribute("textbox-placeholder")) this.setAttribute("textbox-placeholder", "");        
        if (!this.hasAttribute("type")) this.setAttribute("type", "show");
        if (this.hasAttribute("hidden")) this.hideAndShow.click();
        if (this.hasAttribute("read-only")) this.txtSearch.readonly = true;

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

        if(this.getAttribute("type") == "show") {
            this.txtSearch.onIconButtonTextInputIcon_click(async () => {
                this.offset = 0;
                let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, this.txtSearch.value, this.offset, DatabaseTable.RECORDS_SHOWN);
                this.show_updateTable(response);
                this.makeTableVisible(true);
            });
        }

        if(this.getAttribute("type") == "select") {
            this.txtSearch.iconClasses = DatabaseTable.SELECT_TXTINPUT_ICON;
            
            this.txtSearch.onIconButtonTextInputIcon_click(async () => {
                if(this.recordChecked) this.setRecordChecked(this.recordChecked);
                else this.InitializeValues();
                this.makeTableVisible(true);
            });


            this.txtSearch.onTextInput_blur(async () => {
                if(this.txtSearch.readonly) return;
                let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, this.txtSearch.value, this.offset, DatabaseTable.RECORDS_SHOWN);
                this.show_updateTable(response);
                this.makeTableVisible(true);
                this.checkRecord(this.recordChecked);
                this.txtSearch.value = this.recordChecked;
            });

            this.txtSearch.onTextInput_focus(() => {
                if(this.txtSearch.readonly) return;
                this.txtSearch.value = "";
            });
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

                if(this.getAttribute("type") == "show" && this.hasAttribute("destinationURL")) {
                    trow.onclick = () => {
                        let keyName = this.columns.find(c => c.primaryKey)["columnName"];
                        let keyValue = trow.querySelector(".primary-key").innerText;
                        let URL = `${this.getAttribute("destinationURL")}?${keyName}=${keyValue}`;
                        Utilities.ReindirizzaTo(URL);
                    };
                }

                if(this.getAttribute("type") == "select") {
                    trow.onclick = () => this.checkRecord(record[this.columns.find(c => c.primaryKey)["columnName"]]);
                }
                
                this.tbody.appendChild(trow);
            });
        }
    }

    show_updateTableNavigator(searchString, offset, toRetrieve, retrievedRecords, totalRows) {
        this.tableNavigator.classList.remove("invisible-field")

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
            this.checkRecord(this.recordChecked);
        }

        this.btn_nextPage.onclick = async () => {
            this.offset += toRetrieve;
            let response = await DatabaseTable.getRecordsBySearchString(this.allRecords_queryPath, searchString, this.offset, toRetrieve);
            this.show_updateTable(response);
            this.checkRecord(this.recordChecked);
        } 
    }

    select_updateTableNavigator(retrievedRecords) {
        // this.pageResults.innerText = `Risultati ${retrievedRecords} - ${retrievedRecords} di ${retrievedRecords}`;
        // this.btn_previousPage.disabled = true;
        // this.btn_nextPage.disabled = true;  
        this.tableNavigator.classList.add("invisible-field")
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

    }
    
    set value(newValue) {
        console.log("Questa funzione non serve a nulla :)"); 
    }

    getRecordChecked() {
        // let toReturn = undefined;
        // if(this.getAttribute("type") == "select") {
        //     let checkedRow = Array.from(this.tbody.querySelectorAll("tr")).find(row => row.querySelector(".radioButton").checked);
        //     if (checkedRow) toReturn = checkedRow.querySelector(".primary-key").innerText;
        // }
        // return toReturn;
        return this.recordChecked;
    }

    // -> Imposta la riga "spuntata" e la "spunta"
    async setRecordChecked(primaryKey) {
        if(this.getAttribute("type") == "select") {
            let data = [];
            let response = await DatabaseTable.getRecordByPrimaryKey(this.oneRecord_queryPath, primaryKey);
            if (response["record"]) data.push(response["record"]);
            this.updateTableRecords(data);
            this.select_updateTableNavigator(data.length);
            this.checkRecord(primaryKey);
        } else {
            console.log("La tabella deve essere di tipo 'select'!");
        }
    }

    // -> "Spunta" una riga
    checkRecord(primaryKey) {
        if(!primaryKey) return;
        
        let selectedRow = Array.from(this.tbody.querySelectorAll("tr"))
        .find(row => row.querySelector(".primary-key").innerText == primaryKey);

        if(!selectedRow) return;

        let radioButtonChecked = selectedRow.querySelector(".radioButton");

        if(radioButtonChecked) {
            radioButtonChecked.checked = true;
            this.recordChecked = radioButtonChecked.value;
            this.txtSearch.value = this.recordChecked;
        }

    }

    makeTableVisible(toDisplay = undefined) {
        if (toDisplay == undefined) {
            let daNascondere = this.mantelloContainer.classList.toggle("invisible-field");
            this.hideAndShow.innerText = (daNascondere) ? "+" : "-";   
            return;
        }
        
        if(toDisplay)
            this.mantelloContainer.classList.remove("invisible-field");
        else
            this.mantelloContainer.classList.add("invisible-field");

        this.hideAndShow.innerText = (toDisplay) ? "-" : "+";  
        
    }

}

document.currentScript.onload = () => customElements.define("database-table", DatabaseTable);