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
    else if ($task == "getAllEtats"){
        $str_ETAT_ID = "";
        if(isset($_GET['str_ETAT_ID']))
        {
            $str_ETAT_ID = htmlentities($_GET['str_ETAT_ID']);
        }
        getAllEtats($str_ETAT_ID, $db);
    } 
    else if ($task == "deleteEtats"){
        $str_ETAT_ID = $_GET["str_ETAT_ID"];
        deleteEtats($str_ETAT_ID, $db);
    }
} 
else if (isset($_POST['addEtats'])){    
    $str_ETAT_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE']));
    addEtats( $db, $str_LIBELLE);
}
else if (isset($_POST['editeEtats'])) {
    $str_ETAT_ID = htmlentities(trim($_POST['str_ETAT_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    editEtats($str_ETAT_ID, $str_LIBELLE, $db);
}