<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");
    // POSSIBLE RESPONSEs
    // -> authentication = true, false -> Admin autenticato
    // -> newCod = true, false -> If codCategoria is unique
    // -> newDesc = true, false -> If descrizione is unique

    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    $newCat_cod = $_POST["newCat_codice"];
    $newCat_desc = $_POST["newCat_descrizione"];

    $response = array(
        "authentication" => false,
        "newCod" => false,
        "newDesc" => false
    );

    // Authenticate the admin
    $admin_info = existAdmin($connection, $admin_username, $admin_password);

    if(!$admin_info){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;

    // Check for unique codCat
    if(!existCodiceCategoria($connection, $newCat_cod)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newCod"] = true;

    // Check for unique descrizione
    if(!existDescrizioneCategoria($connection, $newCat_desc)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newDesc"] = true;

    addCategory($newCat_cod, $newCat_desc);
    
    echo json_encode($response);
    query_terminate($connection);
?>