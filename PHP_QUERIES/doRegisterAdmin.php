<?php
    include("databaseAccess.php");
    include("utility.php");
    // POSSIBLE RESPONSEs
    // -> authentication = true, false -> Admin autenticato
    // -> newMail = true, false -> If email is unique
    
    $admin_username = $_POST["admin_username"];
    $admin_password = $_POST["admin_password"];
    $newAdmin_mail = $_POST["newAdmin_mail"];
    $newAdmin_passwordInChiaro = generateSecurePassword("potere");
    $newAdmin_password = hash("sha256", $newAdmin_passwordInChiaro, false);

    $response = array(
        "authentication" => false,
        "newMail" => false,
    );

    // Authenticate the admin
    $admin_info = authenticate_admin($connection, $admin_username, $admin_password);

    if(!$admin_info){
        echo json_encode($response);
        query_terminate($connection);  
    }
    $response["authentication"] = true;

    // Check for unique mail
    if(!isNewMail($connection, $newAdmin_mail)) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["newMail"] = true;


    $qCreateAdmin = "INSERT INTO amministratori 
    VALUES(NULL, '$newAdmin_mail','$newAdmin_password','$newAdmin_passwordInChiaro');";

    $connection->query($qCreateAdmin);
    
    echo json_encode($response);
    $connection->close();
?>