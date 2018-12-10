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
    else if ($task == "getAllCollection"){
        $str_COLLECTION_ID = "";
        if(isset($_GET['str_COLLECTION_ID']))
        {
            $str_COLLECTION_ID = htmlentities($_GET['str_COLLECTION_ID']);
        }
        getAllCollection($str_COLLECTION_ID, $db);
    } 
    else if ($task == "deleteCollection"){
        $str_COLLECTION_ID = $_GET["str_COLLECTION_ID"];
        deleteCollection($str_COLLECTION_ID, $db);
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
else if (isset($_POST['editCollection'])) {
    $str_COLLECTION_ID = htmlentities(trim($_POST['str_COLLECTION_ID']));
    $str_LETTRAGE = htmlentities(trim($_POST['str_LETTRAGE']));
    $str_NUMERO_POLICE = htmlentities(trim($_POST['str_NUMERO_POLICE']));
    $int_PRIME_TTC = htmlentities(trim($_POST['int_PRIME_TTC']));
    $dt_EFFET = htmlentities(trim($_POST['dt_EFFET']));
    $dt_ECHEANCE = htmlentities(trim($_POST['dt_ECHEANCE']));
    editCollection($str_COLLECTION_ID, $str_LETTRAGE, $str_NUMERO_POLICE, $int_PRIME_TTC, $dt_EFFET, $dt_ECHEANCE, $db);
}