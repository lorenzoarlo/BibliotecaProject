<?php
    include("../PHP_QUERIES/php-createConnectionDatabase.php");

    $result = $connection->query("SELECT * FROM utenti");
    var_dump($result);
    echo "<p/>";
    echo "<p/>";
    var_dump(mysqli_fetch_assoc($result));
    var_dump(mysqli_fetch_assoc($result));
    echo "<p/>";
    echo "<p/>";
    var_dump($result);

    query_terminate($connection);
?>