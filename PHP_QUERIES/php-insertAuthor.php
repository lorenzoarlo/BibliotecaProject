<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");
    // POSSIBLE RESPONSEs
    // -> authentication = true, false -> Admin autenticato
    // -> newCodAutore = true, false -> If codAutore is unique
    
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    $newAutore_nome = $_POST["newAutore_nome"];
    $newAutore_cognome = $_POST["newAutore_cognome"];
    $newAutore_codAutore = $_POST["newAutore_codAutore"];
    $newAutore_annoNascita = $_POST["newAutore_annoNascita"];
    $newAutore_biografia = $_POST["newAutore_biografia"];

    $response = array(
        "authentication" => false,
        "newCodAutore" => false
    );

    // Authenticate the admin
    $admin_info = existAdmin($connection, $admin_username, $admin_password);

    if(!$admin_info){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;

    // Check for unique mail
    if(!existCodiceAutore($connection, $newAutore_codAutore)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newCodAutore"] = true;

    addAuthor($newAutore_codAutore, $newAutore_nome, $newAutore_cognome, $newAutore_annoNascita, $newAutore_biografia);
    
    echo json_encode($response);
    query_terminate($connection);
?>