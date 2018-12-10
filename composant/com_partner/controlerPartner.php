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
    else if ($task == "getAllPartner"){
        $str_PARTENAIRE_ID = "";
        if(isset($_GET['str_PARTENAIRE_ID']))
        {
            $str_PARTENAIRE_ID = htmlentities($_GET['str_PARTENAIRE_ID']);
        }
        getAllPartner($str_PARTENAIRE_ID, $db);
    } 
    else if ($task == "deletePartner"){
        $str_PARTENAIRE_ID = $_GET["str_PARTENAIRE_ID"];
        deletePartner($str_PARTENAIRE_ID, $db);
    }
} 
else if (isset($_POST['addPartner'])){
    $str_PARTENAIRE_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE']));
    addPartner( $db, $str_LIBELLE);
}
else if (isset($_POST['editePartner'])) {
    $str_PARTENAIRE_ID = htmlentities(trim($_POST['str_PARTENAIRE_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    editePartner($str_PARTENAIRE_ID, $str_LIBELLE, $db);
}