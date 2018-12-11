<?php
	include "lib.php";
    $arrayJson["results"] = "Votre session a expiré, veuillez vous reconnecter s'il vous plait!";
    $arrayJson["desc_statut"] = "Une erreur c'est produite !";
    $arrayJson["code_statut"] = 2;
	if(!isset($_SESSION['str_SECURITY_ID']))
    {
        $arrayJson["results"] = "Votre session a expiré, veuillez vous reconnecter s'il vous plait!";
        $arrayJson["desc_statut"] = "Une erreur c'est produite !";
        $arrayJson["code_statut"] = -1;
        echo "[" . json_encode($arrayJson) . "]";
        die();
    }
    echo "[" . json_encode($arrayJson) . "]";
    die();
?>