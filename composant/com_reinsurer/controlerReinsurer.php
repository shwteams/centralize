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
    else if ($task == "getAllReinsurer"){
        $str_REINSURER_ID = "";
        if(isset($_GET['str_REASSUREUR_ID']))
        {
            $str_REINSURER_ID = htmlentities($_GET['str_REASSUREUR_ID']);
        }
        getAllReinsurer($str_REINSURER_ID, $db);
    } 
    else if ($task == "deleteReinsurer"){
        $str_REINSURER_ID = $_GET["str_REASSUREUR_ID"];
        deleteReinsurer($str_REINSURER_ID, $db);
    }
} 
else if (isset($_POST['addReinsurer'])){
    $str_REINSURER_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_NOM']));
    addReinsurer( $db, $str_LIBELLE);
}
else if (isset($_POST['editeReinsurer'])) {
    $str_REINSURER_ID = htmlentities(trim($_POST['str_REINSURER_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    editeReinsurer($str_REINSURER_ID, $str_LIBELLE, $db);
}