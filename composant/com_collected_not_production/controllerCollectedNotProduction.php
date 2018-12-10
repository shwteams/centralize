<?php
if(!isset($_SESSION))
    session_start();

include '../../services/lib.php';
include_once '../../services/parameters.php';
$db = DoConnexion(HOST, USER, PWD, DBNAME);
if (isset($_GET["task"])) {
    $task = $_GET["task"];
    //echo $task;
    if ($task == "") {
        echo 'Error';
    }
    else if ($task == "getAllCollectedNotProd"){
        $str_COLLECTION_ID = "";
        if(isset($_GET['str_COLLECTION_ID']))
        {
            $str_COLLECTION_ID = htmlentities($_GET['str_COLLECTION_ID']);
        }
        getAllCollectedNotProd($str_COLLECTION_ID, $db);
    }
    else if($task == "getAllCollectedPaie")
    {
        $str_NUMERO_POLICE = htmlentities($_GET['str_NUMERO_POLICE']);
        getAllCollectedPaie($str_NUMERO_POLICE, $db);
    }
    else if ($task == "getAllContract"){
        $str_CONTRACT_ID = "";
        if(isset($_GET['str_CONTRACT_ID']))
        {
            $str_CONTRACT_ID = htmlentities($_GET['str_CONTRACT_ID']);
        }
        getAllContract($str_CONTRACT_ID, $db);
    }
    else if ($task == "getAllIntermediate"){
        $str_INTERMEDIATE_ID = "";
        if(isset($_GET['str_INTERMEDIATE_ID']))
        {
            $str_INTERMEDIATE_ID = htmlentities($_GET['str_INTERMEDIATE_ID']);
        }
        getAllIntermediate($str_INTERMEDIATE_ID, $db);
    }
}
else if (isset($_POST['addProduction'])){
    $str_CONTRACT_ID = htmlentities(trim($_POST['str_CONTRACT_ID']));
    $str_INTERMEDIATE_ID = htmlentities(trim($_POST['str_INTERMEDIATE_ID']));
    $dt_EFFET = htmlentities(trim($_POST['dt_EFFET']));
    $dt_ECHEANCE = htmlentities(trim($_POST['dt_ECHEANCE']));
    $int_PRIME_TTC = htmlentities(trim($_POST['int_PRIME_TTC']));
    $int_COMMISSION = htmlentities(trim($_POST['int_COMMISSION']));
    $int_PRIME_NET = htmlentities(trim($_POST['int_PRIME_NET']));
    $int_TAUX_COMMISSION = htmlentities(trim($_POST['int_TAUX_COMMISSION']));
    $int_AVENANT = htmlentities(trim($_POST['int_AVENANT']));
    $int_MAJO = htmlentities(trim($_POST['int_MAJO']));
    addProduction($db,$str_CONTRACT_ID, $str_INTERMEDIATE_ID, $dt_EFFET, $dt_ECHEANCE, $int_PRIME_TTC, $int_COMMISSION, $int_PRIME_NET, $int_TAUX_COMMISSION, $int_AVENANT, $int_MAJO);
}
else if(isset($_POST['extractXSLData']))
{
    $str_FILE_NAME = htmlentities(trim($_POST['str_FILE_NAME']));

    extractXSLDataCollectedNotProduction($str_FILE_NAME, $db);
}