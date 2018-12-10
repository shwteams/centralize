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
    else if($task == "getProductionStatistical"){
        $str_SINISTRE_ID = '';
        getProductionStatistical($str_SINISTRE_ID, $db);
    }
    else if($task == "getStatisticalSinistreTreat"){
        $str_SINISTRE_ID = '';
        getStatisticalSingetProductionStatisticalistreTreat($str_SINISTRE_ID, $db);
    }
    else if($task == "getAmoutBoniMaliBoniPotentiel"){
        $str_SINISTRE_ID = '';
        getAmoutBoniMaliBoniPotentiel($str_SINISTRE_ID, $db);
    }
}
else if (isset($_POST['editapplication']))
{
}