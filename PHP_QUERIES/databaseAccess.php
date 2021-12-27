<?php
    function query_terminate($connection){
        $connection->close();
        exit();
    }

    $db_server_name = "localhost";
    $db_username = "Bibliotecatore";
    $db_password = "pwd_in_chiaro";
    $db_name = "biblioteca";

    $connection = mysqli_connect($db_server_name, $db_username, $db_password, $db_name);
?>