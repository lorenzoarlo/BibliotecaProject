<?php
    include("php-createConnectionDatabase.php");
    include("phpFunctions-Exist.php");
    include("phpFunctions-Utilities.php");

    $response = array(
        "authentication" => false,
        "mailCollision" => false,
        "codiceFiscaleCollision" => false,
        "codiceCategoriaCollision" => false,
        "codiceAutoreCollision" => false,
        "descrizioneCategoriaCollision" => false,
        "inserted" => false
    );

    $tableName = $_GET["tableName"];

    $adminUsername = $_POST["adminUsername"];
    $adminPassword = $_POST["adminPassword"];
    
    $admin = loginAdmin($connection, $adminUsername, $adminPassword);

    if(!$admin) {
        echo json_encode($response);
        query_terminate($connection);
    }
    $response["authentication"] = true;


    switch($tableName) {
        case "categories":
            $codiceCategoria = $_POST["codiceCategoria"];
            if(exists_codiceCategoria($connection, $codiceCategoria)) {
                $response["codiceCategoriaCollision"] = true;
                break;
            }

            $descrizioneCategoria = $_POST["descrizioneCategoria"];
            if(exists_descrizioneCategoria($connection, $descrizioneCategoria)) {
                $response["descrizioneCategoriaCollision"] = true;
                break;
            }

            $response["inserted"] = insert_category($connection, $codiceCategoria, $descrizioneCategoria);
            break;
        case "authors":
            $codiceAutore = $_POST["codiceAutore"];
            if(exists_codiceAutore($connection, $codiceAutore)) {
                $response["codiceAutoreCollision"] = true;
                break;
            }
            
            $nomeAutore = $_POST["nomeAutore"];
            $cognomeAutore = $_POST["cognomeAutore"];
            $dataNascita = "";
            $biografia = "";
            
            $response["inserted"] = insert_author($connection, $codiceAutore, $nomeAutore, $cognomeAutore, $dataNascita, $biografia);
            break;
        case "users":
            $nomeUtente = $_POST["nomeUtente"];
            $cognomeUtente = $_POST["cognomeUtente"];
            $mailUtente = $_POST["mailUtente"];
            if(exists_mail($connection, $mailUtente)) {
                $response["mailCollision"] = true;
                break;
            }
            
            $codiceFiscale = $_POST["codiceFiscale"];
            if(exists_codiceFiscale($connection, $codiceFiscale)) {
                $response["codiceFiscaleCollision"] = true;
                break;
            }
            
            $codiceTessera = generateCodiceTessera($connection, $mailUtente, $codiceFiscale);
            $passwordInChiaro = generateSecurePassword("brooopo");
            $registrationDate = date("d-m-Y");

            $response["inserted"] = insert_user($connection, $codiceTessera, $nomeUtente, $cognomeUtente, $mailUtente, $registrationDate, $codiceFiscale, $passwordInChiaro, $admin["idAmministratore"]);
            break;
        case "books":
            
            break;
        case "admins":
            $mailAdmin = $_POST["mailAdmin"];
            if(exists_mail($connection, $mailAdmin)) {
                $response["mailCollision"] = true;
                break;
            }
            
            $passwordInChiaro = generateSecurePassword("a");

            $response["inserted"] = insert_admin($connection, $mailAdmin, $passwordInChiaro);
            break;
        case "loans":
            
            break;
        default:
            break;
    }

    echo json_encode($response);
    query_terminate($connection);

    function insert_category($connection, $codiceCategoria, $descrizioneCategoria) {
        $q = "INSERT INTO categorie
        VALUES ('$codiceCategoria','$descrizioneCategoria');";
        
        return $connection->query($q);
    }

    function insert_author($connection, $codiceAutore, $nomeAutore, $cognomeAutore, $dataNascita, $biografia) {
        $q = "INSERT INTO autori
        VALUES ('$codiceAutore','$nomeAutore','$cognomeAutore', NULL, NULL);";
        
        return $connection->query($q);
    }

    function insert_user($connection, $codiceTessera, $nomeUtente, $cognomeUtente, $mailUtente, $registrationDate, $codiceFiscale, $passwordInChiaro, $idAmministratore) {
        $encryptedPassword = hash("sha256", $passwordInChiaro, false);
        $q = "INSERT INTO utenti
        VALUES ('$codiceTessera','$nomeUtente','$cognomeUtente','$mailUtente',STR_TO_DATE('$registrationDate', '%d-%m-%Y'),'$codiceFiscale','$encryptedPassword',$idAmministratore,'$passwordInChiaro');";
        
        return $connection->query($q);
    }

    function insert_admin($connection, $mailAdmin, $passwordInChiaro) {
        $encryptedPassword = hash("sha256", $passwordInChiaro, false);
        $q = "INSERT INTO amministratori
        VALUES (NULL,'$mailAdmin','$encryptedPassword','$passwordInChiaro');";
        
        return $connection->query($q);
    }


?>