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
    else if ($task == "getAllProdNotCollected"){
        $str_PRODUCTION_ID = "";
        if(isset($_GET['str_PRODUCTION_ID']))
        {
            $str_PRODUCTION_ID = htmlentities($_GET['str_PRODUCTION_ID']);
        }
        getAllProdNotCollected($str_PRODUCTION_ID, $db);
    }
}
else if (isset($_POST['addCollection'])){
    $str_COLLECTION_ID = "";
    $str_LETTRAGE = htmlentities(trim($_POST['str_LETTRAGE']));
    $str_NUMERO_POLICE = htmlentities(trim($_POST['str_NUMERO_POLICE']));
    $int_PRIME_TTC = htmlentities(trim($_POST['int_PRIME_TTC']));
    $dt_EFFET = htmlentities(trim($_POST['dt_EFFET']));
    $dt_ECHEANCE = htmlentities(trim($_POST['dt_ECHEANCE']));
    addCollection( $db, $str_LETTRAGE, $str_NUMERO_POLICE, $int_PRIME_TTC, $dt_EFFET, $dt_ECHEANCE);
}
else if(isset($_POST['extractXSLData']))
{
    $str_FILE_NAME = htmlentities(trim($_POST['str_FILE_NAME']));

    extractXSLDataNotCollectedProduction($str_FILE_NAME, $db);
}