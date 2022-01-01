<?php

    $discriminator = $_GET["x"];


    switch($discriminator) {
        case "users":
            echo "utenti";
            break;
        default:
            echo "default";
            break;
    }





?>