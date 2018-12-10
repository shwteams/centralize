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
    else if ($task == "getAllPlugged"){
        $str_PARTENAIRE_ID = "";
        if(isset($_GET['str_PLUGGED_ID']))
        {
            $str_PARTENAIRE_ID = htmlentities($_GET['str_PLUGGED_ID']);
        }
        getAllPlugged($str_PARTENAIRE_ID, $db);
    } 
    else if ($task == "deletePlugged"){
        $str_PARTENAIRE_ID = $_GET["str_PLUGGED_ID"];
        deletePlugged($str_PARTENAIRE_ID, $db);
    }
} 
else if (isset($_POST['addPlugged'])){
    $str_PARTENAIRE_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE']));
    addPlugged( $db, $str_LIBELLE);
}
else if (isset($_POST['editePlugged'])) {
    $str_PARTENAIRE_ID = htmlentities(trim($_POST['str_PLUGGED_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    editePlugged($str_PARTENAIRE_ID, $str_LIBELLE, $db);
}