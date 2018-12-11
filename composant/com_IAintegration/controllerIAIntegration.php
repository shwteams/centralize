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
    if($task == "getAlphabetiqueWord")
    {
        getAlphabetiqueWord();
    }
    if($task == "getDatabaseTables")
    {
        getDatabaseTables($db);
    }
    if($task == "describeTable")
    {
        $table = $_GET['str_TABLES'];
        describeTable($db, $table);
    }
}

?>