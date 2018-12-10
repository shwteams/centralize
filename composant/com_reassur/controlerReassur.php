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
    else if ($task == "getAllReassur"){
        $str_REASSUR_ID = "";
        if(isset($_GET['str_REASSUR_ID']))
        {
            $str_REASSUR_ID = htmlentities($_GET['str_REASSUR_ID']);
        }
        getAllReassur($str_REASSUR_ID, $db);
    } 
    else if ($task == "deleteReassur"){
        $str_REASSUR_ID = $_GET["str_REASSUR_ID"];
        deleteReassur($str_REASSUR_ID, $db);
    }
    else if ($task == "getReassureur"){
        $str_REINSURER_ID = $_GET["str_REINSURER_ID"];
        getAllReinsurer($str_REINSURER_ID, $db);
    }
    else if ($task == "getAvenant"){
        $str_CONTRAT_ID = $_GET["str_CONTRAT_ID"];
        getAvenant($str_CONTRAT_ID, $db);
    }
    else if ($task == "getAllContractWhoHaveProduction"){
        $str_CONTRACT_ID = "";
        if(isset($_GET['str_CONTRACT_ID']))
        {
            $str_CONTRACT_ID = htmlentities($_GET['str_CONTRACT_ID']);
        }
        getAllContractWhoHaveProduction($str_CONTRACT_ID, $db);
    }
} 
else if (isset($_POST['addReassur'])){
    $str_REASSUR_ID = "";
    $str_NUMERO_POLICE = htmlentities(trim($_POST['str_NUMERO_POLICE']));
    $int_AVENANT = htmlentities(trim($_POST['int_AVENANT']));
    $str_REASSUREUR_ID = htmlentities(trim($_POST['str_REASSUREUR_ID']));
    $int_PAR_SUNU = htmlentities(trim($_POST['int_PAR_SUNU']));
    $int_PAR_REASSUREUR = htmlentities(trim($_POST['int_PAR_REASSUREUR']));
    addReassur( $db, $int_AVENANT, $str_REASSUREUR_ID, $int_PAR_SUNU, $int_PAR_REASSUREUR);
}