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

            $response["updated"] = update_category($connection, $originalPK, $codiceCategoria, $descrizioneCategoria);
            break;
        case "authors":
            $codiceAutore = (isset($_POST["codiceAutore"])) ? $_POST["codiceAutore"] : null;
            $nomeAutore = (isset($_POST["nomeAutore"])) ? $_POST["nomeAutore"] : null;
            $cognomeAutore = (isset($_POST["cognomeAutore"])) ? $_POST["cognomeAutore"] : null;
            $biografiaAutore = (isset($_POST["biografiaAutore"])) ? $_POST["biografiaAutore"] : null;
            $dataNascitaAutore = (isset($_POST["dataNascitaAutore"])) ? $_POST["dataNascitaAutore"] : null;

            if(!is_null($codiceAutore) && exists_codiceAutore($connection, $codiceAutore)) break;

            $response["updated"] = update_author($connection, $originalPK, $codiceAutore, $nomeAutore, $cognomeAutore, $dataNascitaAutore, $biografiaAutore);
            break;
        case "users":
            $mailUtente = (isset($_POST["userMail"])) ? $_POST["userMail"] : null;
            $nomeUtente = (isset($_POST["nomeUtente"])) ? $_POST["nomeUtente"] : null;
            $cognomeUtente = (isset($_POST["cognomeUtente"])) ? $_POST["cognomeUtente"] : null;

            if(!is_null($mailUtente) && exists_mail($connection, $mailUtente)) break;

            $response["updated"] = update_user($connection, $originalPK, $nomeUtente, $cognomeUtente, $mailUtente);
            break;
        case "books":
            $numeroInventario = (isset($_POST["numeroInventario"])) ? $_POST["numeroInventario"] : null; 
            $titolo = (isset($_POST["titolo"])) ? $_POST["titolo"] : null;
            $ISBN = (isset($_POST["ISBN"])) ? $_POST["ISBN"] : null;
            $numeroScaffale = (isset($_POST["numeroScaffale"])) ? $_POST["numeroScaffale"] : null;
            $codiceCategoria = (isset($_POST["codiceCategoria"])) ? $_POST["codiceCategoria"] : null;
            $codiceAutore = (isset($_POST["codiceAutore"])) ? $_POST["codiceAutore"] : null;

            if(!is_null($numeroInventario) && exists_numeroInventario($connection, $numeroInventario)) break;

            $response["updated"] = update_book($connection, $originalPK, $numeroInventario, $titolo, $ISBN, $numeroScaffale, $codiceCategoria, $codiceAutore);
            break;
        case "admins":
            $mailAmministratore = (isset($_POST["mailAmministratore"])) ? $_POST["mailAmministratore"] : null;
            
            if(!is_null($mailAmministratore) && exists_mail($connection, $mailAmministratore)) break;

            $response["updated"] = update_admin($connection, $originalPK, $mailAmministratore);
            break;
        case "loans":
            $classeAttuale = (isset($_POST["classeAttuale"])) ? $_POST["classeAttuale"] : null;

            $response["updated"] = update_loan($connection, $originalPK, $classeAttuale, isset($_POST["finePrestito"]));
            break;
        default:
            break;
    }
    $response["uniqueField-collision"] = false;
    echo json_encode($response);
    query_terminate($connection);


    function update_category($connection, $originalPK, $codiceCategoria, $descrizioneCategoria) {
        $q = "UPDATE categorie
        SET";
        if($codiceCategoria) $q = $q . " codiceCategoria = '$codiceCategoria',";
        if($descrizioneCategoria) $q = $q . " descrizioneCategoria = '$descrizioneCategoria',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codiceCategoria = '$originalPK';";

        return $connection->query($q);
    }

    function update_author($connection, $originalPK, $codiceAutore, $nomeAutore, $cognomeAutore, $dataNascitaAutore, $biografiaAutore) {
        $q = "UPDATE autori
        SET";
        if($codiceAutore) $q = $q . " codiceAutore = '$codiceAutore',";
        if($nomeAutore) $q = $q . " nomeAutore = '$nomeAutore',";
        if($cognomeAutore) $q = $q . " cognomeAutore = '$cognomeAutore',";
        if($dataNascitaAutore) $q = $q . " dataNascitaAutore = '$dataNascitaAutor',";
        if($biografiaAutore) $q = $q . " biografiaAutore = '$biografiaAutore',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codiceAutore = '$originalPK';";

        return $connection->query($q);
    }

    function update_user($connection, $originalPK, $nomeUtente, $cognomeUtente, $mailUtente) {
        $q = "UPDATE utenti
        SET";
        if($nomeUtente) $q = $q . " nomeUtente = '$nomeUtente',";
        if($cognomeUtente) $q = $q . " cognomeUtente = '$cognomeUtente',";
        if($mailUtente) $q = $q . " mailUtente = '$mailUtente',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE codiceTessera = '$originalPK';";

        return $connection->query($q);
    }

    function update_book($connection, $originalPK, $numeroInventario, $titolo, $ISBN, $numeroScaffale, $codiceCategoria, $codiceAutore) {
        $q = "UPDATE libri
        SET";
        if($numeroInventario) $q = $q . " numeroInventario = $numeroInventario,";
        if($titolo) $q = $q . " titolo = '$titolo',";
        if($ISBN) $q = $q . " ISBN = $ISBN,";
        if($numeroScaffale) $q = $q . " numeroScaffale = $numeroScaffale,";
        if($codiceCategoria) $q = $q . " codiceCategoria = '$codiceCategoria',";
        if($codiceAutore) $q = $q . " codiceAutore = '$codiceAutore',";
        $q = substr_replace($q, "", -1);
        $q = $q . " WHERE numeroInventario = $originalPK;";

        return $connection->query($q);
    }

    function update_admin($connection, $originalPK, $mailAmministratore) {
        $q = "UPDATE amministratori
        SET";
        if($mailAmministratore) $q = $q . " mailAmministratore = '$mailAmministratore',";
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