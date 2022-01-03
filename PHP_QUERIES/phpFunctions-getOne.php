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
        $q = "SELECT categorie.codCategoria as codiceCategoria,
        categorie.descrizione
        FROM categorie
        WHERE codCategoria = '$primaryKey';";

        return $connection->query($q);
    }

    function get_author($connection, $primaryKey) {
        $q = "SELECT autori.codAutore as codiceAutore,
        autori.nome, 
        autori.cognome
        FROM autori
        WHERE codAutore = '$primaryKey';";

        return $connection->query($q);
    }

    function get_book($connection, $primaryKey) {
        $q = "SELECT libri.nInventario as numeroInventario, 
        libri.titolo, 
        libri.ISBN,
        libri.nScaffale as numeroScaffale, 
        libri.codCategoria as codiceCategoria, 
        libri.codAutore as codiceAutore
        FROM libri
        WHERE nInventario == '$primaryKey';";

        return $connection->query($q);
    }

    function get_user($connection, $primaryKey) {
        $q = "SELECT utenti.codTessera as codiceTessera, 
        utenti.nome, 
        utenti.cognome, 
        utenti.user_mail as userMail, 
        utenti.codFiscale as codiceFiscale, 
        DATE_FORMAT(utenti.dataRegistr, '%d-%m-%Y') as dataRegistrazione, 
        utenti.idAmministratore 
        FROM utenti
        WHERE codTessera = '$primaryKey';";

        return $connection->query($q);
    }

    function get_loan($connection, $primaryKey) {
        $q = "SELECT prestiti.idPrestito, 
        prestiti.codTessera as codiceTessera, 
        prestiti.nInventario as numeroInventario,
        DATE_FORMAT(prestiti.inizioPrestito, '%d-%m-%Y') as inizioPrestito, 
        DATE_FORMAT(prestiti.finePrestito, '%d-%m-%Y') as finePrestito,
        prestiti.classeAttuale, 
        FROM prestiti
        WHERE idPrestito = $primaryKey;";

        return $connection->query($q);
    }

    function get_admin($connection, $primaryKey) {
        $q = "SELECT amministratori.idAmministratore, 
        amministratori.admin_mail as adminMail, 
        FROM amministratori
        WHERE idAmministratore = $primaryKey;";

        return $connection->query($q);
    }
    


?>