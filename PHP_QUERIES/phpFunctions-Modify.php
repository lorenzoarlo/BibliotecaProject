<?php
    // POSSIBLE RESPONSEs
    // -> authentication = true, false
    // -> updated = true, false
    // -> uniqueField-collision = true, false

    include("php-createConnectionDatabase.php");
    include("phpFunctions-Exist.php");
    include("phpFunctions-Utilities.php");

    $response = array(
        "authentication" => false,
        "updated" => false,
        "uniqueField-collision" => true
    );

    $tableName = $_GET["tableName"];

    $adminUsername = $_POST["adminUsername"];
    $adminPassword = $_POST["adminPassword"];

    if(!loginAdmin($connection, $adminUsername, $adminPassword)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["authentication"] = true;

    $originalPK = $_POST["originalPK"];

    switch($tableName) {
        case "categories":
            $codiceCategoria = (isset($_POST["codiceCategoria"])) ? $_POST["codiceCategoria"] : null;
            $descrizioneCategoria = (isset($_POST["descrizioneCategoria"])) ? $_POST["descrizioneCategoria"] : null;

            if(!is_null($codiceCategoria) && exists_codiceCategoria($connection, $codiceCategoria)) break;
            if(!is_null($descrizioneCategoria) && exists_descrizioneCategoria($connection, $descrizioneCategoria)) break;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_category($connection, $originalPK, $codiceCategoria, $descrizioneCategoria);
            break;
        case "authors":
            $codiceAutore = (isset($_POST["codiceAutore"])) ? $_POST["codiceAutore"] : null;
            $nomeAutore = (isset($_POST["nomeAutore"])) ? $_POST["nomeAutore"] : null;
            $cognomeAutore = (isset($_POST["cognomeAutore"])) ? $_POST["cognomeAutore"] : null;

            if(!is_null($codiceAutore) && exists_codiceAutore($connection, $codiceAutore)) break;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_author($connection, $originalPK, $codiceAutore, $nomeAutore, $cognomeAutore);
            break;
        case "users":
            $mailUtente = (isset($_POST["userMail"])) ? $_POST["userMail"] : null;
            $nomeUtente = (isset($_POST["nomeUtente"])) ? $_POST["nomeUtente"] : null;
            $cognomeUtente = (isset($_POST["cognomeUtente"])) ? $_POST["cognomeUtente"] : null;

            if(!is_null($mailUtente) && exists_mail($connection, $mailUtente)) break;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_user($connection, $originalPK, $nomeUtente, $cognomeUtente, $mailUtente);
            break;
        case "books":
            $titolo = (isset($_POST["titolo"])) ? $_POST["titolo"] : null;
            $ISBN = (isset($_POST["ISBN"])) ? $_POST["ISBN"] : null;
            $numeroScaffale = (isset($_POST["numeroScaffale"])) ? $_POST["numeroScaffale"] : null;
            $codiceCategoria = (isset($_POST["codiceCategoria"])) ? $_POST["codiceCategoria"] : null;
            $codiceAutore = (isset($_POST["codiceAutore"])) ? $_POST["codiceAutore"] : null;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_book($connection, $originalPK, $titolo, $ISBN, $numeroScaffale, $codiceCategoria, $codiceAutore);
            break;
        case "admins":
            $adminMail = (isset($_POST["adminMail"])) ? $_POST["adminMail"] : null;
            
            if(!is_null($adminMail) && exists_mail($connection, $adminMail)) break;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_admin($connection, $originalPK, $adminMail);
            break;
        case "loans":
            $classeAttuale = (isset($_POST["classeAttuale"])) ? $_POST["classeAttuale"] : null;

            $response["uniqueField-collision"] = false;
            $response["updated"] = update_loan($connection, $originalPK, $classeAttuale, isset($_POST["finePrestito"]));
            break;
        default:
            break;
    }

    echo json_encode($response);
    query_terminate($connection);


    function update_category($connection, $originalPK, $codiceCategoria, $descrizioneCategoria) {
        $q = "UPDATE categorie
        SET";
        if($codiceCategoria) $q = $q . " codCategoria = '$codiceCategoria',";
        if($descrizioneCategoria) $q = $q . " descrizione = '$descrizioneCategoria',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codCategoria = '$originalPK';";

        return $connection->query($q);
    }

    function update_author($connection, $originalPK, $codiceAutore, $nomeAutore, $cognomeAutore) {
        $q = "UPDATE autori
        SET";
        if($codiceAutore) $q = $q . " codAutore = '$codiceAutore',";
        if($nomeAutore) $q = $q . " nome = '$nomeAutore',";
        if($cognomeAutore) $q = $q . " cognome = '$cognomeAutore',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codAutore = '$originalPK';";

        return $connection->query($q);
    }

    function update_user($connection, $originalPK, $nomeUtente, $cognomeUtente, $mailUtente) {
        $q = "UPDATE utenti
        SET";
        if($nomeUtente) $q = $q . " nome = '$nomeUtente',";
        if($cognomeUtente) $q = $q . " cognome = '$cognomeUtente',";
        if($mailUtente) $q = $q . " user_mail = '$mailUtente',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codTessera = '$originalPK';";

        return $connection->query($q);
    }

    function update_book($connection, $originalPK, $titolo, $ISBN, $numeroScaffale, $codiceCategoria, $codiceAutore) {
        $q = "UPDATE libri
        SET";
        if($titolo) $q = $q . " titolo = '$titolo',";
        if($ISBN) $q = $q . " ISBN = $ISBN,";
        if($numeroScaffale) $q = $q . " nScaffale = $numeroScaffale,";
        if($codiceCategoria) $q = $q . " codCategoria = '$codiceCategoria',";
        if($codiceAutore) $q = $q . " codAutore = '$codiceAutore',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE nInventario = $originalPK;";

        return $connection->query($q);
    }

    function update_admin($connection, $originalPK, $adminMail) {
        $q = "UPDATE amministratori
        SET";
        if($adminMail) $q = $q . " admin_mail = '$adminMail',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE idAmministratore = $originalPK;";

        return $connection->query($q);
    }

    function update_loan($connection, $originalPK, $classeAttuale, $finePrestito) {
        $q = "UPDATE prestiti
        SET";
        if($classeAttuale) $q = $q . " classeAttuale = '$classeAttuale',";
        if($finePrestito) $q = $q . " finePrestito = STR_TO_DATE('" . date("d-m-Y") . "', '%d-%m-%Y'),";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE idPrestito = $originalPK;";
        if($finePrestito) {
            $q = substr_replace($q, "", -1);
            $q = $q . " AND finePrestito IS NULL;";
        }
        return $connection->query($q);
    }



?>