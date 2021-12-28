<?php 
    include("../PHP_QUERIES/databaseAccess.php");
    // -> CATEGORIE
    $file = file('../DATA/categorie.csv');

    $q = "INSERT INTO Categorie VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            $q = $q . $value . ",";
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);
    
    // -> AUTORI
    $file = file('../DATA/autori.csv');

    $q = "INSERT INTO Autori VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            $q = $q . $value . ",";
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);


    // -> AMMINISTRATORI
    $file = file('../DATA/amministratori.csv');

    $q = "INSERT INTO Amministratori VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            $q = $q . $value . ",";
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);

    // -> UTENTI
    $file = file('../DATA/utenti.csv');

    $q = "INSERT INTO Utenti VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            if($key == 4) {
                $q = $q . "STR_TO_DATE(" . $value . ", '%d-%m-%Y'),";
            }
            // else if($key == 8){
            //     continue;
            // }
            else{
                $q = $q . $value . ",";
            }
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);
    
    // -> UTENTI
    $file = file('../DATA/libri.csv');

    $q = "INSERT INTO Libri VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            $q = $q . $value . ",";
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);

    // -> PRESTITI
    $file = file('../DATA/prestiti.csv');

    $q = "INSERT INTO Prestiti VALUES";
    for($i = 1; $i < count($file); $i++) {
        $q = $q . "(";
        foreach(explode(';', $file[$i]) as $key => $value) {
            if(($key == 3 || $key == 4) && ($value != 'NULL')) {
                $q = $q . "STR_TO_DATE(" . $value . ", '%d-%m-%Y'),";
            }else{
                $q = $q . $value . ",";
            }
        }
        $q = substr_replace($q, "", -1);
        $q = $q . "),";
    }
    $q = substr_replace($q, ";", -1);
    $connection->query($q);


    $connection->close();
    echo "php> DATABASE RIEMPITO"
?>