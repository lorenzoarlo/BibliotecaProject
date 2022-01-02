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



?>