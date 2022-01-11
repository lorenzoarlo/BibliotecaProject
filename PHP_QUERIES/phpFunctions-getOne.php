<?php
    include("php-createConnectionDatabase.php");

    $tableName = $_GET["tableName"];
    $primaryKey = $_POST["primaryKey"];

    $response = array(
        "error" => false,
        "record" => null
    );

    $result = null;

    switch($tableName) {
        case "categories":
            $result = get_category($connection, $primaryKey);
            break;
        case "authors":
            $result = get_author($connection, $primaryKey);
            break;
        case "books":
            $result = get_book($connection, $primaryKey);
            break;
        case "loans":
            $result = get_loan($connection, $primaryKey);
            break;
        case "users":
            $result = get_user($connection, $primaryKey);
            break;
        case "admins":
            $result = get_admin($connection, $primaryKey);
            break;
        default:
            $response["error"] = true;
            break;
    }

    while($record = mysqli_fetch_assoc($result)) {
        $response["record"] = $record;
    }
    
    echo json_encode($response);
    query_terminate($connection);

    function get_category($connection, $primaryKey) {
        $q = "SELECT categorie.codiceCategoria,
        categorie.descrizioneCategoria
        FROM categorie
        WHERE codiceCategoria = '$primaryKey';";

        return $connection->query($q);
    }

    function get_author($connection, $primaryKey) {
        $q = "SELECT autori.codiceAutore,
        autori.nomeAutore, 
        autori.cognomeAutore,
        CONCAT(autori.nomeAutore, ' ', autori.cognomeAutore) as nomeCompletoAutore
        FROM autori
        WHERE codiceAutore = '$primaryKey';";

        return $connection->query($q);
    }

    function get_book($connection, $primaryKey) {
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
        WHERE numeroInventario = $primaryKey;";

        return $connection->query($q);
    }

    function get_user($connection, $primaryKey) {
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
        WHERE codiceTessera = '$primaryKey';";

        return $connection->query($q);
    }

    function get_loan($connection, $primaryKey) {
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
        WHERE idPrestito = $primaryKey;";

        return $connection->query($q);
    }

    function get_admin($connection, $primaryKey) {
        $q = "SELECT amministratori.idAmministratore, 
        amministratori.mailAmministratore
        FROM amministratori
        WHERE idAmministratore = $primaryKey;";

        return $connection->query($q);
    }
    


?>