<?php

    function loginAdmin($connection, $username, $password) {
        $encryptedPassword = hash("sha256", $password, false);

        $q = "SELECT amministratori.idAmministratore, 
        amministratori.mailAmministratore, 
        amministratori.passwordAmministratore 
        FROM amministratori 
        WHERE mailAmministratore = '$username' 
        AND passwordAmministratore = '$encryptedPassword';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;
    }

    function loginUser($connection, $username, $password) {
        $encryptedPassword = hash("sha256", $password, false);
        
        $q = "SELECT utenti.codiceTessera,
        utenti.mailUtente,
        utenti.passwordUtente
        FROM utenti 
        WHERE (mailUtente = '$username' OR codiceTessera = '$username')
        AND passwordUtente = '$encryptedPassword';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;
    }

    // ----- GENERATE FUNCTIONS -----

    function generateSecurePassword($word) {
        return $word;
    }

    function generateCodiceTessera($connection, $mail, $cf) {
        $user_exists = true;
        $iterator = 0;
        $encrypted = "";
        do {
            $toEncrypt = "$mail" . "$cf" . "$iterator";
            $encrypted = substr(hash("sha256", $toEncrypt, false), 0, 8);
            $qUser = "SELECT utenti.codiceTessera
        FROM utenti
        WHERE codiceTessera = '$encrypted';";
            $result = $connection->query($qUser);
            $iterator++;
        }while($result->num_rows > 0);
        return $encrypted;
    }

?>