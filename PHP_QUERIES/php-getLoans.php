<?php
    include("php-createConnectionDatabase.php");
    include("php-functionUtilities.php");
    // POSSIBLE RESPONSEs
    // -> totalRows = int
    // -> data = [{},{}]
    
    $search_string = $_POST["search_string"];
    $offset = $_POST["offset"];
    $nRecords = $_POST["nRecords"];

    $response = array(
        "totalRows" => 0,
        "data" => []
    );

    list($response["totalRows"], $response["data"]) = getLoans($connection, $search_string, $offset, $nRecords);
    
    echo json_encode($response);
    query_terminate($connection);
?>