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
    else if ($task == "getAllAutreObservation"){
        $str_AUTRE_OBSERVATION_ID = "";
        if(isset($_GET['str_AUTRE_OBSERVATION_ID']))
        {
            $str_AUTRE_OBSERVATION_ID = htmlentities($_GET['str_AUTRE_OBSERVATION_ID']);
        }
        getAllAutreObservation($str_AUTRE_OBSERVATION_ID, $db);
    } 
    else if ($task == "deleteAutreObservation"){
        $str_AUTRE_OBSERVATION_ID = $_GET["str_AUTRE_OBSERVATION_ID"];
        deleteAutreObservation($str_AUTRE_OBSERVATION_ID, $db);
    }
} 
else if (isset($_POST['addAutreObservation'])){    
    $str_AUTRE_OBSERVATION_ID = "";
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE']));
    addAutreObservation( $db, $str_LIBELLE);
}
else if (isset($_POST['editeAutreObservation'])) {
    $str_AUTRE_OBSERVATION_ID = htmlentities(trim($_POST['str_AUTRE_OBSERVATION_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    editAutreObservation($str_AUTRE_OBSERVATION_ID, $str_LIBELLE, $db);
}