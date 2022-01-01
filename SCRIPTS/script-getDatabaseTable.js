const RECORDS_TO_DISPLAY = 5;

function get_all_users(containerID, searchString=null) {
    if(searchString == null) searchString = document.querySelector(`#${containerID} .txtInput-container .txtInput`).value;
    let toSend = new FormData();

    let offset = parseInt(document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset);
    let nRecords = RECORDS_TO_DISPLAY;

    toSend.append("search_string", searchString);
    toSend.append("offset", offset);
    toSend.append("nRecords", nRecords);

    let alertParentElement = document.querySelector(".default-container");

    fetch("../PHP_QUERIES/php-getUsers.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);

            let tbody = document.querySelector(`#${containerID} .database-table tbody`);
            Utility.RemoveAllChildren(tbody);

            let finalResult = offset + output["data"].length;
            let offsetToDisplay = (output["data"].length == 0) ? 0 : offset + 1; 

            let prevBtn = document.querySelector(`#${containerID} .table-navigator .previous`);
            let nextBtn = document.querySelector(`#${containerID} .table-navigator .next`);
            let resultPages = document.querySelector(`#${containerID} .table-navigator .result-pages`);
            resultPages.innerText = `Risultati ${offsetToDisplay} - ${finalResult} di ${output["totalRows"]}`; 
            prevBtn.disabled = (offset == 0);
            nextBtn.disabled = (finalResult == output["totalRows"]);

            prevBtn.onclick = () => {
                resultPages.dataset.offset = `${offset - nRecords}`;
                get_all_users(containerID, searchString);
            };

            nextBtn.onclick = () => {
                resultPages.dataset.offset = `${offset + nRecords}`;
                get_all_users(containerID, searchString);
            };

            if(output["data"].length == 0) {
                let trow = document.createElement("tr");

                let noResult = document.createElement("td");
                noResult.innerText = "Nessun risultato trovato";
                noResult.colSpan = 4;
                trow.appendChild(noResult);

                tbody.appendChild(trow);   
            }

            output["data"].forEach(record => {
                let trow = document.createElement("tr");

                let codTessera = document.createElement("td");
                codTessera.innerText = record.codTessera;
                codTessera.className = "primary-key";
                trow.appendChild(codTessera);

                let nome = document.createElement("td");
                nome.innerText = `${record.nome} ${record.cognome}`;
                trow.appendChild(nome);

                let mail = document.createElement("td");
                mail.innerText = record.user_mail;
                trow.appendChild(mail); 

                let codFiscale = document.createElement("td");
                codFiscale.innerText = record.codFiscale;
                trow.appendChild(codFiscale);

                // Row OnClick function
                trow.addEventListener("click", () => {
                    let primaryKey = trow.querySelector(".primary-key");
                    console.log(primaryKey.innerText);
                })

                tbody.appendChild(trow);
            });
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
}

function get_all_authors(containerID, searchString=null) {
    searchString = (searchString == null) ? document.querySelector(`#${containerID} .txtInput-container .txtInput`).value : searchString;
    let toSend = new FormData();

    let offset = parseInt(document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset);
    let nRecords = RECORDS_TO_DISPLAY;

    toSend.append("search_string", searchString);
    toSend.append("offset", offset);
    toSend.append("nRecords", nRecords);

    let alertParentElement = document.querySelector(".default-container");

    fetch("../PHP_QUERIES/php-getAuthors.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);

            let tbody = document.querySelector(`#${containerID} .database-table tbody`);
            Utility.RemoveAllChildren(tbody);

            let finalResult = offset + output["data"].length;
            let offsetToDisplay = (output["data"].length == 0) ? 0 : offset + 1; 

            let prevBtn = document.querySelector(`#${containerID} .table-navigator .previous`);
            let nextBtn = document.querySelector(`#${containerID} .table-navigator .next`);
            let resultPages = document.querySelector(`#${containerID} .table-navigator .result-pages`);
            resultPages.innerText = `Risultati ${offsetToDisplay} - ${finalResult} di ${output["totalRows"]}`; 
            prevBtn.disabled = (offset == 0);
            nextBtn.disabled = (finalResult == output["totalRows"]);

            prevBtn.onclick = () => {
                resultPages.dataset.offset = `${offset - nRecords}`;
                get_all_authors(containerID, searchString);
            };

            nextBtn.onclick = () => {
                resultPages.dataset.offset = `${offset + nRecords}`;
                get_all_authors(containerID, searchString);
            };

            if(output["data"].length == 0) {
                let trow = document.createElement("tr");

                let noResult = document.createElement("td");
                noResult.innerText = "Nessun risultato trovato";
                noResult.colSpan = 2;
                trow.appendChild(noResult);

                tbody.appendChild(trow);   
            }

            output["data"].forEach(record => {
                let trow = document.createElement("tr");

                let codAutore = document.createElement("td");
                codAutore.innerText = record.codAutore;
                codAutore.className = "primary-key";
                trow.appendChild(codAutore);

                let nome = document.createElement("td");
                nome.innerText = `${record.nome} ${record.cognome}`;
                trow.appendChild(nome);

                // Row OnClick function
                trow.addEventListener("click", () => {
                    let primaryKey = trow.querySelector(".primary-key");
                    console.log(primaryKey.innerText);
                })

                tbody.appendChild(trow);
            });
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
}

function get_all_categories(containerID, searchString=null) {
    searchString = (searchString == null) ? document.querySelector(`#${containerID} .txtInput-container .txtInput`).value : searchString;
    let toSend = new FormData();

    let offset = parseInt(document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset);
    let nRecords = RECORDS_TO_DISPLAY;

    toSend.append("search_string", searchString);
    toSend.append("offset", offset);
    toSend.append("nRecords", nRecords);

    let alertParentElement = document.querySelector(".default-container");

    fetch("../PHP_QUERIES/php-getCategories.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);

            let tbody = document.querySelector(`#${containerID} .database-table tbody`);
            Utility.RemoveAllChildren(tbody);

            let finalResult = offset + output["data"].length;
            let offsetToDisplay = (output["data"].length == 0) ? 0 : offset + 1; 

            let prevBtn = document.querySelector(`#${containerID} .table-navigator .previous`);
            let nextBtn = document.querySelector(`#${containerID} .table-navigator .next`);
            let resultPages = document.querySelector(`#${containerID} .table-navigator .result-pages`);
            resultPages.innerText = `Risultati ${offsetToDisplay} - ${finalResult} di ${output["totalRows"]}`; 
            prevBtn.disabled = (offset == 0);
            nextBtn.disabled = (finalResult == output["totalRows"]);

            prevBtn.onclick = () => {
                resultPages.dataset.offset = `${offset - nRecords}`;
                get_all_categories(containerID, searchString);
            };

            nextBtn.onclick = () => {
                resultPages.dataset.offset = `${offset + nRecords}`;
                get_all_categories(containerID, searchString);
            };

            if(output["data"].length == 0) {
                let trow = document.createElement("tr");

                let noResult = document.createElement("td");
                noResult.innerText = "Nessun risultato trovato";
                noResult.colSpan = 2;
                trow.appendChild(noResult);

                tbody.appendChild(trow);   
            }

            output["data"].forEach(record => {
                let trow = document.createElement("tr");

                let codCategoria = document.createElement("td");
                codCategoria.innerText = record.codCategoria;
                codCategoria.className = "primary-key";
                trow.appendChild(codCategoria);

                let descrizione = document.createElement("td");
                descrizione.innerText = record.descrizione;
                trow.appendChild(descrizione);

                // Row OnClick function
                trow.addEventListener("click", () => {
                    let primaryKey = trow.querySelector(".primary-key");
                    console.log(primaryKey.innerText);
                })

                tbody.appendChild(trow);
            });
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
}

function get_all_books(containerID, searchString=null) {
    searchString = (searchString == null) ? document.querySelector(`#${containerID} .txtInput-container .txtInput`).value : searchString;
    let toSend = new FormData();

    let offset = parseInt(document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset);
    let nRecords = RECORDS_TO_DISPLAY;

    toSend.append("search_string", searchString);
    toSend.append("offset", offset);
    toSend.append("nRecords", nRecords);

    let alertParentElement = document.querySelector(".default-container");

    fetch("../PHP_QUERIES/php-getBooks.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            let output = JSON.parse(response);

            let tbody = document.querySelector(`#${containerID} .database-table tbody`);
            Utility.RemoveAllChildren(tbody);

            let finalResult = offset + output["data"].length;
            let offsetToDisplay = (output["data"].length == 0) ? 0 : offset + 1; 

            let prevBtn = document.querySelector(`#${containerID} .table-navigator .previous`);
            let nextBtn = document.querySelector(`#${containerID} .table-navigator .next`);
            let resultPages = document.querySelector(`#${containerID} .table-navigator .result-pages`);
            resultPages.innerText = `Risultati ${offsetToDisplay} - ${finalResult} di ${output["totalRows"]}`; 
            prevBtn.disabled = (offset == 0);
            nextBtn.disabled = (finalResult == output["totalRows"]);

            prevBtn.onclick = () => {
                resultPages.dataset.offset = `${offset - nRecords}`;
                get_all_books(containerID, searchString);
            };

            nextBtn.onclick = () => {
                resultPages.dataset.offset = `${offset + nRecords}`;
                get_all_books(containerID, searchString);
            };

            if(output["data"].length == 0) {
                let trow = document.createElement("tr");

                let noResult = document.createElement("td");
                noResult.innerText = "Nessun risultato trovato";
                noResult.colSpan = 6;
                trow.appendChild(noResult);

                tbody.appendChild(trow);   
            }

            output["data"].forEach(record => {
                let trow = document.createElement("tr");

                let nInventario = document.createElement("td");
                nInventario.innerText = record.nInventario;
                nInventario.className = "primary-key";
                trow.appendChild(nInventario);

                let titolo = document.createElement("td");
                titolo.innerText = record.titolo;
                trow.appendChild(titolo);

                let isbn = document.createElement("td");
                isbn.innerText = record.ISBN;
                trow.appendChild(isbn);

                let descrizione = document.createElement("td");
                descrizione.innerText = record.descrizione;
                trow.appendChild(descrizione);

                let autore = document.createElement("td");
                autore.innerText = `${record.nome} ${record.cognome}`;
                trow.appendChild(autore);

                // let codAutore = document.createElement("td");
                // codAutore.innerText = record.codAutore;
                // trow.appendChild(codAutore);

                let nScaffale = document.createElement("td");
                nScaffale.innerText = record.nScaffale;
                trow.appendChild(nScaffale);


                // Row OnClick function
                trow.addEventListener("click", () => {
                    let primaryKey = trow.querySelector(".primary-key");
                    console.log(primaryKey.innerText);
                })

                tbody.appendChild(trow);
            });
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
}

function get_all_loans(containerID, searchString=null) {
    searchString = (searchString == null) ? document.querySelector(`#${containerID} .txtInput-container .txtInput`).value : searchString;
    let toSend = new FormData();

    let offset = parseInt(document.querySelector(`#${containerID} .table-navigator .result-pages`).dataset.offset);
    let nRecords = RECORDS_TO_DISPLAY;

    toSend.append("search_string", searchString);
    toSend.append("offset", offset);
    toSend.append("nRecords", nRecords);

    let alertParentElement = document.querySelector(".default-container");

    fetch("../PHP_QUERIES/php-getLoans.php", { method: "POST", body: toSend })
        .then((response) => response.text())
        .then(function (response) {
            console.log(response);
            let output = JSON.parse(response);

            let tbody = document.querySelector(`#${containerID} .database-table tbody`);
            Utility.RemoveAllChildren(tbody);

            let finalResult = offset + output["data"].length;
            let offsetToDisplay = (output["data"].length == 0) ? 0 : offset + 1; 

            let prevBtn = document.querySelector(`#${containerID} .table-navigator .previous`);
            let nextBtn = document.querySelector(`#${containerID} .table-navigator .next`);
            let resultPages = document.querySelector(`#${containerID} .table-navigator .result-pages`);
            resultPages.innerText = `Risultati ${offsetToDisplay} - ${finalResult} di ${output["totalRows"]}`; 
            prevBtn.disabled = (offset == 0);
            nextBtn.disabled = (finalResult == output["totalRows"]);

            prevBtn.onclick = () => {
                resultPages.dataset.offset = `${offset - nRecords}`;
                get_all_loans(containerID, searchString);
            };

            nextBtn.onclick = () => {
                resultPages.dataset.offset = `${offset + nRecords}`;
                get_all_loans(containerID, searchString);
            };

            if(output["data"].length == 0) {
                let trow = document.createElement("tr");

                let noResult = document.createElement("td");
                noResult.innerText = "Nessun risultato trovato";
                noResult.colSpan = 5;
                trow.appendChild(noResult);

                tbody.appendChild(trow);   
            }

            output["data"].forEach(record => {
                let trow = document.createElement("tr");

                let idPrestito = document.createElement("td");
                idPrestito.innerText = record.idPrestito;
                idPrestito.className = "primary-key notDisplay-field";
                trow.appendChild(idPrestito);

                let codTessera = document.createElement("td");
                codTessera.innerText = record.codTessera;
                trow.appendChild(codTessera);
                
                let utente = document.createElement("td");
                utente.innerText = `${record.utente_nome} ${record.utente_cognome}`;
                trow.appendChild(utente);

                let titolo = document.createElement("td");
                titolo.innerText = record.titolo;
                trow.appendChild(titolo);

                let inizioPrestito = document.createElement("td");
                inizioPrestito.innerText = record.inizioPrestito;
                trow.appendChild(inizioPrestito);

                let finePrestito = document.createElement("td");
                finePrestito.innerText = record.finePrestito;
                trow.appendChild(finePrestito);

                // Row OnClick function
                trow.addEventListener("click", () => {
                    let primaryKey = trow.querySelector(".primary-key");
                    console.log(primaryKey.innerText);
                })

                tbody.appendChild(trow);
            });
        })
        .catch(error => {
            Utility.Summon_ErrorAlert(alertParentElement, "Errore di connessione!");  
        })
}