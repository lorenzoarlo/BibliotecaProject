<?php 
    include("../PHP_QUERIES/databaseAccess.php");

    $q = file_get_contents("query_resetDatabase.sql");
    $connection->query('SET foreign_key_checks = 0');
    foreach(explode(";", $q) as $singleQ) {
        if(strlen($singleQ) > 0) $result = $connection->query($singleQ . ";");
    }
    $connection->query('SET foreign_key_checks = 1');
    
    $connection->close();
    echo "php> DATABASE INIZIALIZZATO"
?>