<?php
    include("databaseAccess.php");
    include("utility.php");
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
    $admin_info = authenticate_admin($connection, $admin_username, $admin_password);

    if(!$admin_info){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;

    // Check for unique codCat
    if(!isNewCodCategoria($connection, $newCat_cod)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newCod"] = true;

    // Check for unique descrizione
    if(!isNewDescCategoria($connection, $newCat_desc)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newDesc"] = true;

    $qCreateCat = "INSERT INTO categorie 
    VALUES('$newCat_cod', '$newCat_desc');";

    $connection->query($qCreateCat);
    
    echo json_encode($response);
    $connection->close();
?>