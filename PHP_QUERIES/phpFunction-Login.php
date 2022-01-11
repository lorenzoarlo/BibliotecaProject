<?php
    // POSSIBLE RESPONSEs
    // -> authentication = true, false
    // -> user-type = admin, user, null

    include("php-createConnectionDatabase.php");
    include("phpFunctions-Utilities.php");

    $loginUsername = $_POST["loginUsername"];
    $loginPassword = $_POST["loginPassword"];

    $response = array(
        "authentication" => false,
        "user-type" => null,
        "primary-key" => null
    );
    
    $amministratore = loginAdmin($connection, $loginUsername, $loginPassword); 
    if($amministratore) {
        $response["authentication"] = true;
        $response["primary-key"] = $amministratore["idAmministratore"];
        $response["user-type"] = "amministratore";
        echo json_encode($response);
        query_terminate($connection);
    }

    $utente = loginUser($connection, $loginUsername, $loginPassword); 
    if($utente) {
        $response["authentication"] = true;
        $response["primary-key"] = $utente["codiceTessera"];
        $response["user-type"] = "utente";
    }

    echo json_encode($response);
    query_terminate($connection);
?>