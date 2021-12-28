<?php
    // POSSIBLE RESPONSEs
    // -> authentication = true, false
    // -> user-type = admin, user, null


    $login_username = $_POST["username"];
    $login_password = hash("sha256", $_POST["password"], false);

    include("databaseAccess.php");
    
    $response = array(
        "authentication" => false,
        "user-type" => null
    );
    
    // SEARCH IN amministratori
    $qAdmin = "SELECT amministratori.admin_mail, amministratori.admin_password 
    FROM amministratori 
    WHERE admin_mail = '$login_username';";
    
    $resultAdmin = $connection->query($qAdmin);


    // EMAIL CORRISPONDE IN amministratori
    if($resultAdmin->num_rows > 0) {
        $record = $resultAdmin->fetch_assoc();

        if($record["admin_password"] == $login_password){
            $response["authentication"] = true;
            $response["user-type"] = "admin";
        }
        
        echo json_encode($response);
        
        // Al posto di exit();
        query_terminate($connection);
    }

    // SEARCH IN utenti
    $qUser = "SELECT utenti.user_mail, utenti.user_password, utenti.codTessera 
    FROM utenti 
    WHERE user_mail = '$login_username' OR codTessera = '$login_username';";

    $resultUser = $connection->query($qUser);

    if($resultUser->num_rows > 0) {
        $record = $resultUser->fetch_assoc();

        if($record["user_password"] == $login_password){
            $response["authentication"] = true;
            $response["user-type"] = "user";
        }
    }

    echo json_encode($response);
    $connection->close();
?>