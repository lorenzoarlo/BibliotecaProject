<?php 
    function exists_mail($connection, $mailValue) {
        $qAdmin = "SELECT amministratori.admin_mail
        FROM amministratori
        WHERE admin_mail = '$mailValue';";

        $result = $connection->query($qAdmin);

        if($result->num_rows > 0) return true;

        $qUser = "SELECT utenti.user_mail
        FROM utenti
        WHERE user_mail = '$mailValue';";

        $result = $connection->query($qUser);

        return $result->num_rows > 0;
    }

    function exists_codiceFiscale($connection, $valueCodiceFiscale) {
        $qUser = "SELECT utenti.codFiscale
        FROM utenti
        WHERE codFiscale = '$valueCodiceFiscale';";

        $result = $connection->query($qUser);

        return $result->num_rows > 0;
    }

    function exists_codiceCategoria($connection, $valueCodiceCategoria) {
        $q = "SELECT categorie.codCategoria
        FROM categorie
        WHERE codCategoria = '$valueCodiceCategoria';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_descrizioneCategoria($connection, $valueDescrizioneCategoria) {
        $q = "SELECT categorie.descrizione
        FROM categorie
        WHERE descrizione = '$valueDescrizioneCategoria';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_codiceAutore($connection, $valueCodiceAutore) {
        $q = "SELECT autori.codAutore
        FROM autori
        WHERE codAutore = '$valueCodiceAutore';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_numeroInventario($connection, $valueNumeroInventario) {
        $q = "SELECT libri.nInventario
        FROM libri
        WHERE nInventario = $valueNumeroInventario;";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

?>