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
    else if ($task == "getAllIntermediate"){
        $str_INTERMEDIATE_ID = "";
        if(isset($_GET['str_INTERMEDIATE_ID']))
        {
            $str_INTERMEDIATE_ID = htmlentities($_GET['str_INTERMEDIATE_ID']);
        }
        getAllIntermediate($str_INTERMEDIATE_ID, $db);
    } 
    else if ($task == "deleteIntermediate"){
        $str_INTERMEDIATE_ID = $_GET["str_INTERMEDIATE_ID"];
        deleteIntermediate($str_INTERMEDIATE_ID, $db);
    }
} 
else if (isset($_POST['addIntermediate'])){
    $str_INTERMEDIATE_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_NOM']));
    $str_CODE = htmlentities(trim($_POST['str_CODE']));
    addIntermediate( $db, $str_LIBELLE, $str_CODE);
}
else if (isset($_POST['editeIntermediate'])) {
    $str_INTERMEDIATE_ID = htmlentities(trim($_POST['str_INTERMEDIATE_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_NOM_EDIT']));
    $str_CODE = htmlentities(trim($_POST['str_CODE_EDIT']));
    editeIntermediate($str_INTERMEDIATE_ID, $str_LIBELLE, $str_CODE, $db);
}