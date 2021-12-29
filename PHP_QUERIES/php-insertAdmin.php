<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");
    // POSSIBLE RESPONSEs
    // -> authentication = true, false -> Admin autenticato
    // -> newMail = true, false -> If email is unique
    
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    $newAdmin_mail = $_POST["newAdmin_mail"];
    $newAdmin_passwordInChiaro = generateSecurePassword("potere");

    $response = array(
        "authentication" => false,
        "newMail" => false,
    );

    // Authenticate the admin
    $authenticatedAdmin = ;

    if(!existAdmin($connection, $admin_username, $admin_password)){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;

    // Check for unique mail
    if(!existMail($connection, $newAdmin_mail)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newMail"] = true;

    addAdmin($connection, $newAdmin_mail, $newAdmin_password);

    $q = "INSERT INTO amministratori 
    VALUES(NULL, '$newAdmin_mail','$newAdmin_password','$newAdmin_passwordInChiaro');";

    $connection->query($q);
    
    echo json_encode($response);
    query_terminate($connection);
?>