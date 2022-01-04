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
        "user-type" => null,
        "primary-key" => null
    );
    
    $admin = loginAdmin($connection, $loginUsername, $loginPassword); 
    if($admin) {
        $response["authentication"] = true;
        $response["primary-key"] = $admin["idAmministratore"];
        $response["user-type"] = "admin";
        echo json_encode($response);
        query_terminate($connection);
    }

    $user = loginUser($connection, $loginUsername, $loginPassword); 
    if($user) {
        $response["authentication"] = true;
        $response["primary-key"] = $user["codTessera"];
        $response["user-type"] = "user";
    }

    echo json_encode($response);
    query_terminate($connection);
?>