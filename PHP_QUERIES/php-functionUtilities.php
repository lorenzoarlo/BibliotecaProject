<?php
    // ----- GET -----

    function getUsers($connection, $searchString, $offset, $nRecords) {
        $searchString = "%" . strtoupper($searchString) . "%";

        $q = "SELECT utenti.codTessera, utenti.nome, utenti.cognome, utenti.user_mail, utenti.codFiscale, utenti.dataRegistr , utenti.idAmministratore
        FROM utenti
        WHERE (UPPER(codTessera) LIKE '$searchString') 
        OR (UPPER(nome) LIKE '$searchString') 
        OR (UPPER(cognome) LIKE '$searchString') 
        OR (UPPER(user_mail) LIKE '$searchString')
        OR (UPPER(codFiscale) LIKE '$searchString');";

        $result = $connection->query($q);

        $output = array();
        $nRecords = ($nRecords < 0) ? $result->num_rows : $nRecords;

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $nRecords) break;
        }

        return array($result->num_rows, $output);
    }

    function getCategories($connection, $searchString, $offset, $nRecords) {
        $searchString = "%" . strtoupper($searchString) . "%";

        $q = "SELECT categorie.codCategoria, categorie.descrizione
        FROM categorie
        WHERE (UPPER(codCategoria) LIKE '$searchString') 
        OR (UPPER(descrizione) LIKE '$searchString');";

        $result = $connection->query($q);

        $output = array();
        $nRecords = ($nRecords < 0) ? $result->num_rows : $nRecords;

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $nRecords) break;
        }

        return array($result->num_rows, $output);
    }

    function getAuthors($connection, $searchString, $offset, $nRecords) {
        $searchString = "%" . strtoupper($searchString) . "%";

        $q = "SELECT autori.codAutore, autori.nome, autori.cognome
        FROM autori
        WHERE (UPPER(codAutore) LIKE '$searchString') 
        OR (UPPER(nome) LIKE '$searchString')
        OR (UPPER(cognome) LIKE '$searchString');";

        $result = $connection->query($q);

        $output = array();
        $nRecords = ($nRecords < 0) ? $result->num_rows : $nRecords;

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $nRecords) break;
        }

        return array($result->num_rows, $output);
    }

    function getBooks($connection, $searchString, $offset, $nRecords) {
        $searchString = "%" . strtoupper($searchString) . "%";

        $q = "SELECT libri.nInventario, libri.titolo, libri.ISBN, libri.nScaffale, libri.codCategoria, libri.codAutore, categorie.descrizione, autori.nome, autori.cognome
        FROM libri 
        NATURAL JOIN categorie 
        NATURAL JOIN autori
        WHERE (CAST(nInventario as char) LIKE '$searchString') 
        OR (UPPER(titolo) LIKE '$searchString')
        OR (CAST(ISBN as char) LIKE '$searchString')
        OR (CAST(nScaffale as char) LIKE '$searchString')
        -- OR (UPPER(codCategoria) LIKE '$searchString')
        -- OR (UPPER(codAutore) LIKE '$searchString')
        OR (UPPER(descrizione) LIKE '$searchString')
        OR (UPPER(nome) LIKE '$searchString')
        OR (UPPER(cognome) LIKE '$searchString');";

        $result = $connection->query($q);

        $output = array();
        $nRecords = ($nRecords < 0) ? $result->num_rows : $nRecords;

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $nRecords) break;
        }

        return array($result->num_rows, $output);
    }

    function getLoans($connection, $searchString, $offset, $nRecords) {
        $searchString = "%" . strtoupper($searchString) . "%";

        $q = "SELECT prestiti.idPrestito, prestiti.codTessera, prestiti.nInventario, prestiti.inizioPrestito, prestiti.finePrestito, prestiti.classeAttuale, utenti.nome as utente_nome, utenti.cognome as utente_cognome, libri.titolo, libri.ISBN   
        FROM prestiti
        NATURAL JOIN utenti 
        NATURAL JOIN libri
        WHERE (UPPER(codTessera) LIKE '$searchString') 
        OR (UPPER(@utente_nome) LIKE '$searchString')
        OR (UPPER(@utente_cognome) LIKE '$searchString')
        OR (CAST(inizioPrestito as char) LIKE '$searchString')
        OR (CAST(finePrestito as char) LIKE '$searchString')
        OR (UPPER(titolo) LIKE '$searchString');";

        $result = $connection->query($q);

        $output = array();
        $nRecords = ($nRecords < 0) ? $result->num_rows : $nRecords;

        $iOffset = 0;
        $inserted = 0;
        while($row = mysqli_fetch_assoc($result)) {
            if($iOffset < $offset) {
                $iOffset++;
                continue;
            }
            $inserted++;
            $output[] = $row;
            if($inserted >= $nRecords) break;
        }

        return array($result->num_rows, $output);
    }


    // ----- ADD -----

    function addAdmin($connection, $username, $password) {
        $password_encrypted = hash("sha256", $password, false);

        $q = "INSERT INTO amministratori 
        VALUES(NULL, '$username','$password_encrypted','$password');";

        $connection->query($q);
    }

    function addUser($connection, $codTessera, $name, $surname, $mail, $date, $codiceFiscale, $password, $idAdmin) {
        $password_encrypted = hash("sha256", $password, false);

        $q = "INSERT INTO utenti
        VALUES ('$codTessera','$name','$surname','$mail','STR_TO_DATE('$date', '%d-%m-%Y'),'$codiceFiscale','$password_encrypted','$idAdmin','$password');";

        $connection->query($q);
    }

    function addCategory($connection, $codCategoria, $descrizione) {
        $q = "INSERT INTO categorie
        VALUES('$codCategoria', '$descrizione');";

        $connection->query($q);
    }

    function addAuthor($connection, $codAutore, $name, $surname, $annoNascita, $biografia) {
        $annoNascita = ($annoNascita == "") ? "NULL" : "$annoNascita";
        $biografia = ($biografia == "") ? "NULL" : "'$biografia'";
        
        $q = "INSERT INTO autori
        VALUES('$codAutore','$name','$surname',$annoNascita, $biografia);";

        $connection->query($q);
    }

    // ----- EXIST -----
    function existAdmin($connection, $username, $password) {
        $password = hash("sha256", $password, false);
        $q = "SELECT amministratori.idAmministratore, amministratori.admin_mail, amministratori.admin_password 
        FROM amministratori 
        WHERE admin_mail = '$username' AND admin_password = '$password';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;
    }

    function existUser($connection, $username, $password) {
        $password = hash("sha256", $password, false);
        $q = "SELECT utenti.codTessera, utenti.user_mail, utenti.user_password 
        FROM utenti 
        WHERE (user_mail = '$username' OR codTessera = '$username') AND user_password = '$password';";
        
        $result = $connection->query($q);
        
        $output = array();
        if($result->num_rows > 0) $output = $result->fetch_assoc();

        return $output;   
    }

    function existMail($connection, $mail) {
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

    function existCodiceFiscale($connection, $cf) {
        $qUser = "SELECT utenti.codFiscale
        FROM utenti
        WHERE codFiscale = '$cf';";

        $result = $connection->query($qUser);

        return $result->num_rows == 0;
    }

    function existCodiceCategoria($connection, $cod) {
        $q = "SELECT categorie.codCategoria
        FROM categorie
        WHERE codCategoria = '$cod';";

        $result = $connection->query($q);

        return $result->num_rows == 0;
    }

    function existDescrizioneCategoria($connection, $desc) {
        $q = "SELECT categorie.descrizione
        FROM categorie
        WHERE descrizione = '$desc';";

        $result = $connection->query($q);

        return $result->num_rows == 0;
    }

    function existCodiceAutore($connection, $cod) {
        $q = "SELECT autori.codAutore
        FROM autori
        WHERE codAutore = '$cod';";

        $result = $connection->query($q);

        return $result->num_rows == 0;

    }


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