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
        $q = "SELECT categorie.codCategoria as codiceCategoria,
        categorie.descrizione
        FROM categorie
        WHERE (UPPER(@codiceCategoria) LIKE '$searchString') 
        OR (UPPER(descrizione) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_authors($connection, $searchString) {
        $q = "SELECT autori.codAutore as codiceAutore,
        autori.nome, 
        autori.cognome,
        CONCAT(autori.nome, ' ', autori.cognome) as nomeCompleto
        FROM autori
        WHERE (UPPER(codAutore) LIKE '$searchString') 
        OR (UPPER(@nomeCompleto) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_books($connection, $searchString) {
        $q = "SELECT libri.nInventario as numeroInventario, 
        libri.titolo, 
        libri.ISBN,
        libri.nScaffale as numeroScaffale, 
        libri.codCategoria, 
        libri.codAutore,
        categorie.descrizione as descrizioneCategoria, 
        autori.nome, 
        autori.cognome,
        CONCAT(autori.nome, ' ', autori.cognome) as nomeCompletoAutore
        FROM libri 
        NATURAL JOIN categorie 
        NATURAL JOIN autori
        WHERE (CAST(@numeroInventario as char) LIKE '$searchString') 
        OR (UPPER(titolo) LIKE '$searchString')
        OR (CAST(ISBN as char) LIKE '$searchString')
        OR (UPPER(@descrizioneCategoria) LIKE '$searchString')
        OR (UPPER(@nomeCompletoAutore) LIKE '$searchString')
        OR (CAST(@numeroScaffale as char) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_users($connection, $searchString) {
        $q = "SELECT utenti.codTessera as codiceTessera, 
        utenti.nome, 
        utenti.cognome, 
        utenti.user_mail as userMail, 
        utenti.codFiscale as codiceFiscale, 
        utenti.dataRegistr as dataRegistrazione, 
        utenti.idAmministratore, 
        CONCAT(utenti.nome, ' ', utenti.cognome) as nomeCompleto
        FROM utenti
        WHERE (UPPER(codTessera) LIKE '$searchString') 
        OR (UPPER(@nomeCompleto) LIKE '$searchString')  
        OR (UPPER(user_mail) LIKE '$searchString')
        OR (UPPER(codFiscale) LIKE '$searchString');";

        return $connection->query($q);
    }

    function get_all_loans($connection, $searchString) {
        $q = "SELECT prestiti.idPrestito, 
        prestiti.codTessera as codiceTessera, 
        prestiti.nInventario as numeroInventario,
        DATE_FORMAT(prestiti.inizioPrestito, '%d-%m-%Y') as inizioPrestito, 
        DATE_FORMAT(prestiti.finePrestito, '%d-%m-%Y') as finePrestito,
        prestiti.classeAttuale,
        CONCAT(utenti.nome, ' ', utenti.cognome) as nomeCompletoUtente, 
        libri.titolo 
        FROM prestiti
        NATURAL JOIN utenti 
        NATURAL JOIN libri
        WHERE (UPPER(codTessera) LIKE '$searchString') 
        OR (UPPER(@nomeCompletoUtente) LIKE '$searchString')
        OR (CAST(@inizioPrestito as char) LIKE '$searchString')
        OR (CAST(@finePrestito as char) LIKE '$searchString')
        OR (UPPER(titolo) LIKE '$searchString');";

        return $connection->query($q);
    }
    


?>