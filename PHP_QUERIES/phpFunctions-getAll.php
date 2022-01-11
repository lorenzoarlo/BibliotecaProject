<?php
    include("php-createConnectionDatabase.php");

    $tableName = $_GET["tableName"];
    $searchString = $_POST["searchString"];
    $offset = $_POST["offset"];
    $retrieveRecords = $_POST["retrieveRecords"];
    
    $response = array(
        "error" => false,
        "totalRows" => 0,
        "data" => []
    );

    $result = null;
    $searchString = fix_searchString($searchString);

    switch($tableName) {
        case "categories":
            $result = get_all_categories($connection, $searchString);
            break;
        case "authors":
            $result = get_all_authors($connection, $searchString);
            break;
        case "books":
            $result = get_all_books($connection, $searchString);
            break;
        case "loans":
            $result = get_all_loans($connection, $searchString);
            break;
        case "users":
            $result = get_all_users($connection, $searchString);
            break;
        case "admins":
            $result = get_all_admins($connection, $searchString);
            break;
        case "usersNotLoan":
            $result = get_all_usersNotLoan($connection, $searchString);
            break;
        case "booksNotLoan":
            $result = get_all_booksNotLoan($connection, $searchString);
            break;
        default:
            $response["error"] = true;
            break;
    }

    $response["totalRows"] = $result->num_rows;
    $response["data"] = fix_response($result, $offset, $retrieveRecords);

    echo json_encode($response);
    query_terminate($connection);

    function fix_searchString($text) {
        return "%" . strtoupper($text) . "%";
    }

    function fix_response($result, $offset, $retrieveRecords) {
        $retrieveRecords = ($retrieveRecords < 0) ? $result->num_rows : $retrieveRecords;
        $output = array();

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $retrieveRecords) break;
        }

        return $output;
    }

    function get_all_categories($connection, $searchString) {
        $q = "SELECT categorie.codiceCategoria,
        categorie.descrizioneCategoria
        FROM categorie
        WHERE (UPPER(codiceCategoria) LIKE '$searchString') 
        OR (UPPER(descrizioneCategoria) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_authors($connection, $searchString) {
        $q = "SELECT autori.codiceAutore,
        autori.nomeAutore, 
        autori.cognomeAutore,
        CONCAT(autori.nomeAutore, ' ', autori.cognomeAutore) as nomeCompletoAutore
        FROM autori
        WHERE (UPPER(codiceAutore) LIKE '$searchString') 
        OR (UPPER(CONCAT(nomeAutore, ' ', cognomeAutore)) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_books($connection, $searchString) {
        $q = "SELECT libri.numeroInventario, 
        libri.titolo, 
        libri.ISBN,
        libri.numeroScaffale, 
        libri.codiceCategoria, 
        categorie.descrizioneCategoria, 
        libri.codiceAutore,
        autori.nomeAutore, 
        autori.cognomeAutore,
        CONCAT(nomeAutore, ' ', cognomeAutore) as nomeCompletoAutore
        FROM libri
        NATURAL JOIN categorie 
        NATURAL JOIN autori
        WHERE (CAST(numeroInventario as char) LIKE '$searchString') 
        OR (UPPER(titolo) LIKE '$searchString')
        OR (CAST(ISBN as char) LIKE '$searchString')
        OR (CAST(numeroScaffale as char) LIKE '$searchString')
        OR (UPPER(codiceCategoria) LIKE '$searchString')
        OR (UPPER(descrizioneCategoria) LIKE '$searchString')
        OR (UPPER(codiceAutore) LIKE '$searchString')
        OR (UPPER(CONCAT(nomeAutore, ' ', cognomeAutore)) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_booksNotLoan($connection, $searchString) {
        $q = "SELECT libri.numeroInventario, 
        libri.titolo, 
        libri.ISBN,
        libri.numeroScaffale, 
        libri.codiceCategoria, 
        categorie.descrizioneCategoria, 
        libri.codiceAutore,
        autori.nomeAutore, 
        autori.cognomeAutore,
        CONCAT(nomeAutore, ' ', cognomeAutore) as nomeCompletoAutore
        FROM libri
        NATURAL JOIN categorie 
        NATURAL JOIN autori
        NATURAL LEFT JOIN prestiti
        WHERE (inizioPrestito IS NULL OR (finePrestito IS NOT NULL)) AND
        (CAST(numeroInventario as char) LIKE '$searchString') AND
        ((UPPER(titolo) LIKE '$searchString')
        OR (CAST(ISBN as char) LIKE '$searchString')
        OR (CAST(numeroScaffale as char) LIKE '$searchString')
        OR (UPPER(codiceCategoria) LIKE '$searchString')
        OR (UPPER(descrizioneCategoria) LIKE '$searchString')
        OR (UPPER(codiceAutore) LIKE '$searchString')
        OR (UPPER(CONCAT(nomeAutore, ' ', cognomeAutore)) LIKE '$searchString'))
        GROUP BY numeroInventario DESC;";

        return $connection->query($q);
    }

    function get_all_admins($connection, $searchString) {
        $q = "SELECT amministratori.idAmministratore, 
        amministratori.mailAmministratore
        FROM amministratori
        WHERE (UPPER(mailAmministratore) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_users($connection, $searchString) {
        $q = "SELECT utenti.codiceTessera, 
        utenti.nomeUtente, 
        utenti.cognomeUtente, 
        utenti.mailUtente, 
        utenti.codiceFiscale, 
        DATE_FORMAT(utenti.dataRegistrazioneUtente, '%d-%m-%Y') as dataRegistrazioneUtente, 
        utenti.idAmministratore,
        amministratori.mailAmministratore,
        CONCAT(nomeUtente, ' ', cognomeUtente) as nomeCompletoUtente
        FROM utenti
        NATURAL JOIN amministratori
        WHERE (UPPER(codiceTessera) LIKE '$searchString') 
        OR (UPPER(CONCAT(nomeUtente, ' ', cognomeUtente)) LIKE '$searchString')
        OR (UPPER(codiceFiscale) LIKE '$searchString')
        OR (UPPER(DATE_FORMAT(dataRegistrazioneUtente, '%d-%m-%Y')) LIKE '$searchString')  
        OR (UPPER(mailUtente) LIKE '$searchString')
        OR (UPPER(mailAmministratore) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_usersNotLoan($connection, $searchString) {
        $q = "SELECT utenti.codiceTessera, 
        utenti.nomeUtente, 
        utenti.cognomeUtente, 
        utenti.mailUtente, 
        utenti.codiceFiscale, 
        DATE_FORMAT(utenti.dataRegistrazioneUtente, '%d-%m-%Y') as dataRegistrazioneUtente, 
        utenti.idAmministratore,
        amministratori.mailAmministratore,
        CONCAT(nomeUtente, ' ', cognomeUtente) as nomeCompletoUtente
        FROM utenti
        NATURAL JOIN amministratori
        NATURAL LEFT JOIN prestiti
        WHERE (inizioPrestito IS NULL OR finePrestito IS NOT NULL) AND
        ((UPPER(codiceTessera) LIKE '$searchString') 
        OR (UPPER(CONCAT(nomeUtente, ' ', cognomeUtente)) LIKE '$searchString')
        OR (UPPER(codiceFiscale) LIKE '$searchString')
        OR (UPPER(DATE_FORMAT(dataRegistrazioneUtente, '%d-%m-%Y')) LIKE '$searchString')  
        OR (UPPER(mailUtente) LIKE '$searchString')
        OR (UPPER(mailAmministratore) LIKE '$searchString'))
        GROUP BY codiceTessera DESC;";
        
        return $connection->query($q);
    }

    function get_all_loans($connection, $searchString) {
        $q = "SELECT prestiti.idPrestito, 
        prestiti.codiceTessera, 
        utenti.nomeUtente, 
        utenti.cognomeUtente, 
        utenti.mailUtente, 
        utenti.codiceFiscale, 
        CONCAT(nomeUtente, ' ', cognomeUtente) as nomeCompletoUtente,
        prestiti.numeroInventario,
        libri.titolo,
        libri.ISBN,
        libri.numeroScaffale, 
        libri.codiceCategoria, 
        categorie.descrizioneCategoria, 
        libri.codiceAutore,
        autori.nomeAutore, 
        autori.cognomeAutore,
        CONCAT(nomeAutore, ' ', cognomeAutore) as nomeCompletoAutore,
        DATE_FORMAT(prestiti.inizioPrestito, '%d-%m-%Y') as inizioPrestito, 
        DATE_FORMAT(prestiti.finePrestito, '%d-%m-%Y') as finePrestito,
        prestiti.classeAttuale
        FROM prestiti
        NATURAL JOIN libri
        NATURAL JOIN utenti
        NATURAL JOIN autori
        NATURAL JOIN categorie
        WHERE (UPPER(codiceTessera) LIKE '$searchString')
        OR (UPPER(CONCAT(nomeUtente, ' ', cognomeUtente)) LIKE '$searchString')
        OR (UPPER(mailUtente) LIKE '$searchString')
        OR (UPPER(codiceFiscale) LIKE '$searchString')
        OR (UPPER(mailUtente) LIKE '$searchString')
        OR (UPPER(codiceFiscale) LIKE '$searchString')
        OR (CAST(numeroInventario as char) LIKE '$searchString')
        OR (UPPER(titolo) LIKE '$searchString')
        OR (CAST(ISBN as char) LIKE '$searchString')
        OR (CAST(numeroScaffale as char) LIKE '$searchString')
        OR (UPPER(descrizioneCategoria) LIKE '$searchString')
        OR (UPPER(CONCAT(nomeAutore, ' ', cognomeAutore)) LIKE '$searchString')
        OR (DATE_FORMAT(inizioPrestito, '%d-%m-%Y') LIKE '$searchString')
        OR (DATE_FORMAT(finePrestito, '%d-%m-%Y') LIKE '$searchString')
        OR (UPPER(classeAttuale) LIKE '$searchString');";

        return $connection->query($q);
    }
    


?>