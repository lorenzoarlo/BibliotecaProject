<?php

    function loginAdmin($connection, $username, $visiblePassword) {
        $password = hash("sha256", $visiblePassword, false);

        $q = "SELECT amministratori.idAmministratore, 
        amministratori.admin_mail, 
        amministratori.admin_password 
        FROM amministratori 
        WHERE admin_mail = '$username' 
        AND admin_password = '$password';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;
    }

    function loginUser($connection, $username, $visiblePassword) {
        $password = hash("sha256", $visiblePassword, false);
        
        $q = "SELECT utenti.codTessera,
        utenti.user_mail,
        utenti.user_password
        FROM utenti 
        WHERE (user_mail = '$username' OR codTessera = '$username')
        AND user_password = '$password';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;
    }

        

    // function addAdmin($connection, $username, $password) {
    //     $password_encrypted = hash("sha256", $password, false);

    //     $q = "INSERT INTO amministratori 
    //     VALUES(NULL, '$username','$password_encrypted','$password');";

    //     $connection->query($q);
    // }

    // function addUser($connection, $codTessera, $name, $surname, $mail, $date, $codiceFiscale, $password, $idAdmin) {
    //     $password_encrypted = hash("sha256", $password, false);

    //     $q = "INSERT INTO utenti
    //     VALUES ('$codTessera','$name','$surname','$mail','STR_TO_DATE('$date', '%d-%m-%Y'),'$codiceFiscale','$password_encrypted','$idAdmin','$password');";

    //     $connection->query($q);
    // }

    // function addCategory($connection, $codCategoria, $descrizione) {
    //     $q = "INSERT INTO categorie
    //     VALUES('$codCategoria', '$descrizione');";

    //     $connection->query($q);
    // }

    // function addAuthor($connection, $codAutore, $name, $surname, $annoNascita, $biografia) {
    //     $annoNascita = ($annoNascita == "") ? "NULL" : "$annoNascita";
    //     $biografia = ($biografia == "") ? "NULL" : "'$biografia'";
        
    //     $q = "INSERT INTO autori
    //     VALUES('$codAutore','$name','$surname',$annoNascita, $biografia);";

    //     $connection->query($q);
    // }

    


    // ----- GENERATE FUNCTIONS
    function generateSecurePassword($name) {
        return $name;
    }

    function generateCodiceTessera($connection, $mail, $cf) {
        $user_exists = true;
        $iterator = 0;
        $encrypted = "";
        do {
            $toEncrypt = "$mail" . "$cf" . "$iterator";
            $encrypted = substr(hash("sha256", $toEncrypt, false), 0, 8);
            $qUser = "SELECT utenti.codTessera
        FROM utenti
        WHERE codTessera = '$encrypted';";
            $result = $connection->query($qUser);
            $iterator++;
        }while($result->num_rows > 0);
        return $encrypted;
    }

?>