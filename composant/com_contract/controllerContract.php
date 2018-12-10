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
    else if ($task == "getAllContract"){
        $str_CONTRACT_ID = "";
        if(isset($_GET['str_CONTRACT_ID']))
        {
            $str_CONTRACT_ID = htmlentities($_GET['str_CONTRACT_ID']);
        }
        getAllContract($str_CONTRACT_ID, $db);
    }
    else if ($task == "deleteContract"){
        $str_CONTRACT_ID = $_GET["str_CONTRACT_ID"];
        deleteContract($str_CONTRACT_ID, $db);
    }
    else if ($task == "getAllPlugged"){
        $str_PLUGGED_ID = $_GET["str_PLUGGED_ID"];
        getAllPlugged( $str_PLUGGED_ID, $db);
    }
    else if ($task == "getAllProductByPlugged"){
        $str_PLUGGED_ID = $_GET["str_PLUGGED_ID"];
        getAllProductByPlugged($str_PLUGGED_ID, $db);
    }
    else if ($task == "getAllCustomer"){
        $str_CUSTOMER_ID = $_GET["str_CUSTOMER_ID"];
        getAllCustomer($str_CUSTOMER_ID, $db);
    }
}
else if (isset($_POST['addContract'])){
    $str_CONTRACT_ID = '';
    $str_PLUGGED_ID = htmlentities(trim($_POST['str_PLUGGED_ID']));
    $str_PRODUIT_ID = htmlentities(trim($_POST['str_PRODUIT_ID']));
    $str_ETAT = htmlentities(trim($_POST['str_ETAT']));
    $str_POLICENUMBER = htmlentities(trim($_POST['str_POLICENUMBER']));
    $str_CUSTOMER_ID = htmlentities(trim($_POST['str_CUSTOMER_ID']));
    if(isset($_POST['str_MOTIF']))
        $str_MOTIF = htmlentities(trim($_POST['str_MOTIF']));
    $int_CARNUMBER = NULL;
    if(isset($_POST['int_NUNBERCAR']) && !empty($_POST['int_NUNBERCAR']))
        $int_CARNUMBER = (int) htmlentities(trim($_POST['int_NUNBERCAR']));

    addContract($db, $str_CONTRACT_ID, $str_PRODUIT_ID, $str_ETAT, $str_POLICENUMBER, $str_MOTIF, $int_CARNUMBER, $str_CUSTOMER_ID);
}
else if (isset($_POST['editeContract'])) {
    $str_CONTRACT_EDIT_ID = htmlentities(trim($_POST['str_CONTRACT_EDIT_ID']));
    $str_PLUGGED_ID = htmlentities(trim($_POST['str_PLUGGED_ID']));
    $str_PRODUIT_ID = htmlentities(trim($_POST['str_PRODUIT_ID']));
    $str_ETAT = htmlentities(trim($_POST['str_ETAT']));
    $str_POLICENUMBER = htmlentities(trim($_POST['str_POLICENUMBER']));
    $str_CUSTOMER_ID = htmlentities(trim($_POST['str_CUSTOMER_ID']));
    if(isset($_POST['str_MOTIF']))
        $str_MOTIF = htmlentities(trim($_POST['str_MOTIF']));
    $int_CARNUMBER = NULL;
    if(isset($_POST['int_NUNBERCAR']) && !empty($_POST['int_NUNBERCAR']) && $_POST['int_NUNBERCAR'] <> '')
        $int_CARNUMBER = (int) htmlentities(trim($_POST['int_NUNBERCAR']));

    editeContract($db, $str_CONTRACT_EDIT_ID, $str_PRODUIT_ID, $str_ETAT, $str_POLICENUMBER, $str_MOTIF, $int_CARNUMBER, $str_CUSTOMER_ID);
}