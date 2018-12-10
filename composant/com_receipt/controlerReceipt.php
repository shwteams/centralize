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
    else if ($task == "getAllReceipt"){
        $str_RECEIPT_ID = "";
        if(isset($_GET['str_RECEIPT_ID']))
        {
            $str_RECEIPT_ID = htmlentities($_GET['str_RECEIPT_ID']);
        }
        getAllReceipt($str_RECEIPT_ID, $db);
    } 
    else if ($task == "deleteReceipt"){
        $str_RECEIPT_ID = $_GET["str_RECEIPT_ID"];
        deleteReceipt($str_RECEIPT_ID, $db);
    }
    else if ($task == "getAllCollection"){
        $str_COLLECTION_ID = "";
        if(isset($_GET['str_COLLECTION_ID']))
        {
            $str_COLLECTION_ID = htmlentities($_GET['str_COLLECTION_ID']);
        }
        getAllCollection($str_COLLECTION_ID, $db);
    }
} 
else if (isset($_POST['addReceipt'])){
    $str_RECEIPT_ID = "";
    $str_QUITTANCE = htmlentities(trim($_POST['str_QUITTANCE']));
    $str_LETTRAGE = htmlentities(trim($_POST['str_LETTRAGE']));
    addReceipt( $db, $str_QUITTANCE, $str_LETTRAGE);
}
else if (isset($_POST['editReceipt'])) {
    $str_QUITTANCE = htmlentities(trim($_POST['str_QUITTANCE_EDIT']));
    $str_LETTRAGE = htmlentities(trim($_POST['str_LETTRAGE_EDIT']));
    $str_QUITTANCE_ID = htmlentities(trim($_POST['str_QUITTANCE_ID']));
    editReceipt($str_QUITTANCE, $str_LETTRAGE, $str_QUITTANCE_ID, $db);
}