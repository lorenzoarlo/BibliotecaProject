<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");

    // POSSIBLE RESPONSEs
    // -> authentication = true, false
    // -> user-type = admin, user, null


    $login_username = $_POST["username"];
    $login_password = $_POST["password"];

    
    $response = array(
        "authentication" => false,
        "user-type" => null
    );
    
    if(existAdmin($connection, $login_username, $login_password)) {
        $response["authentication"] = true;
        $response["user-type"] = "admin";
        echo json_encode($response);
        query_terminate($connection);
    }

    if(existUser($connection, $login_username, $login_password)) {
        $response["authentication"] = true;
        $response["user-type"] = "user";
    }

    echo json_encode($response);
    query_terminate($connection);
?>