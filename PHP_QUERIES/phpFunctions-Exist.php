<?php 
    function exists_mail($connection, $mail) {
        $qAdmin = "SELECT amministratori.mailAmministratore
        FROM amministratori
        WHERE mailAmministratore = '$mail';";

        $result = $connection->query($qAdmin);

        if($result->num_rows > 0) return true;

        $qUser = "SELECT utenti.mailUtente
        FROM utenti
        WHERE mailUtente = '$mail';";

        $result = $connection->query($qUser);

        return $result->num_rows > 0;
    }

    function exists_codiceFiscale($connection, $codiceFiscale) {
        $qUser = "SELECT utenti.codiceFiscale
        FROM utenti
        WHERE codiceFiscale = '$codiceFiscale';";

        $result = $connection->query($qUser);

        return $result->num_rows > 0;
    }

    function exists_codiceCategoria($connection, $codiceCategoria) {
        $q = "SELECT categorie.codiceCategoria
        FROM categorie
        WHERE codiceCategoria = '$codiceCategoria';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_descrizioneCategoria($connection, $descrizioneCategoria) {
        $q = "SELECT categorie.descrizioneCategoria
        FROM categorie
        WHERE descrizioneCategoria = '$descrizioneCategoria';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_codiceAutore($connection, $codiceAutore) {
        $q = "SELECT autori.codiceAutore
        FROM autori
        WHERE codiceAutore = '$codiceAutore';";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

    function exists_numeroInventario($connection, $numeroInventario) {
        $q = "SELECT libri.numeroInventario
        FROM libri
        WHERE numeroInventario = $numeroInventario;";

        $result = $connection->query($q);

        return $result->num_rows > 0;
    }

?>