<?php
    // POSSIBLE RESPONSEs
    // -> authentication = true, false
    // -> user-type = admin, user, null

    include("php-createConnectionDatabase.php");
    include("phpFunctions-Utilities.php");

    $loginUsername = $_POST["username"];
    $loginPassword = $_POST["password"];

    $response = array(
        "authentication" => false,
        "user-type" => null
    );
    
    if(loginAdmin($connection, $loginUsername, $loginPassword)) {
        $response["authentication"] = true;
        $response["user-type"] = "admin";
        echo json_encode($response);
        query_terminate($connection);
    }

    if(loginUser($connection, $loginUsername, $loginPassword)) {
        $response["authentication"] = true;
        $response["user-type"] = "user";
    }

    echo json_encode($response);
    query_terminate($connection);
?>