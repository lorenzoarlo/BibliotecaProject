<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");
    // POSSIBLE RESPONSEs
    // -> authentication = true, false -> Admin autenticato
    // -> newMail = true, false -> If email is unique
    // -> newCF = true, false -> if CF is unique
    
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    $newUser_name = $_POST["newUser_name"];
    $newUser_surname = $_POST["newUser_surname"];
    $newUser_mail = $_POST["newUser_mail"];
    $newUser_codiceFiscale = $_POST["newUser_codiceFiscale"];
    $newUser_passwordInChiaro = generateSecurePassword($newUser_name);
    $newUser_registerDate = date("d-m-Y");

    $response = array(
        "authentication" => false,
        "newMail" => false,
        "newCF" => false
    );

    // Authenticate the admin
    $admin_info = existAdmin($connection, $admin_username, $admin_password);

    if(!$admin_info){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;
    $idAdmin = $admin_info["idAmministratore"];

    // Check for unique mail
    if(!existMail($connection, $newUser_mail)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newMail"] = true;

    // Check for unique CF
    if(!existCodiceFiscale($connection, $newUser_codiceFiscale)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newCF"] = true;

    $newUser_codTessera = generateCodiceTessera($connection, $newUser_mail, $newUser_codiceFiscale);

    addUser($connection, $newUser_codTessera, $newUser_name, $newUser_surname, $newUser_mail, $newUser_registerDate, $newUser_codiceFiscale, $newUser_passwordInChiaro, $idAdmin);
    
    query_terminate($connection);
?>