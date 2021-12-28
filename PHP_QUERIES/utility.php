<?php
    function authenticate_admin($connection, $username, $password) {
        // SEARCH IN amministratori
        $password = hash("sha256", $password, false);
        $q = "SELECT amministratori.idAmministratore, amministratori.admin_mail, amministratori.admin_password 
        FROM amministratori 
        WHERE admin_mail = '$username' AND admin_password = '$password';";
        
        $result = $connection->query($q);
        $output = array();

        if($result->num_rows > 0) {
            $output = $result->fetch_assoc();
        }

        return $output;
    }

    function authenticate_user($connection, $username, $password) {
        
    }

    function isNewMail($connection, $mail) {
        $qAdmin = "SELECT amministratori.admin_mail
        FROM amministratori
        WHERE admin_mail = '$mail';";

        $result = $connection->query($qAdmin);

        if($result->num_rows > 0) return false;

        $qUser = "SELECT utenti.user_mail
        FROM utenti
        WHERE user_mail = '$mail';";

        $result = $connection->query($qUser);

        if($result->num_rows > 0) return false;
        
        return true;
    }

    function isNewCodiceFiscale($connection, $cf) {
        $qUser = "SELECT utenti.codFiscale
        FROM utenti
        WHERE codFiscale = '$cf';";

        $result = $connection->query($qUser);

        return $result->num_rows == 0;
    }

    function isNewCodCategoria($connection, $cod) {
        $q = "SELECT categorie.codCategoria
        FROM categorie
        WHERE codCategoria = '$cod';";

        $result = $connection->query($q);

        return $result->num_rows == 0;
    }

    function isNewDescCategoria($connection, $desc) {
        $q = "SELECT categorie.descrizione
        FROM categorie
        WHERE descrizione = '$desc';";

        $result = $connection->query($q);

        return $result->num_rows == 0;
    }


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