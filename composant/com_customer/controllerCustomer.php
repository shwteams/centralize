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
    else if ($task == "getAllCustomer"){
        $str_CUSTOMER_ID = "";
        if(isset($_GET['str_CUSTOMER_ID']))
        {
            $str_CUSTOMER_ID = htmlentities($_GET['str_CUSTOMER_ID']);
        }
        getAllCustomer($str_CUSTOMER_ID, $db);
    }
    else if ($task == "deleteCustomer"){
        $str_CUSTOMER_ID = $_GET["str_CUSTOMER_ID"];
        deleteCustomer($str_CUSTOMER_ID, $db);
    }
    else if ($task == "getAllPartner"){
        $str_PARTNER_ID = $_GET["str_PARTNER_ID"];
        getAllPartner( $str_PARTNER_ID, $db);
    }
    else if ($task == "searchCustomer"){
        $str_CUSTOMER_NAME = $_GET["str_CUSTOMER_NAME"];
        getCustomerByName($str_CUSTOMER_NAME, $db);
    }
}
else if (isset($_POST['addCustomer'])){
    $str_LIBELLE = htmlentities(trim($_POST['str_NOM']));
    $str_PARTNER_ID = htmlentities(trim($_POST['str_PARTNER_ID']));
    addCustomer($db, $str_LIBELLE, $str_PARTNER_ID);
}
else if (isset($_POST['editeCustomer'])) {
    $str_PARTNER_EDIT_ID = htmlentities(trim($_POST['str_PARTNER_EDIT_ID']));
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    $str_CUSTOMER_ID = htmlentities(trim($_POST['str_CUSTOMER_ID']));
    //exit();
    editeCustomer($str_PARTNER_EDIT_ID, $str_LIBELLE, $str_CUSTOMER_ID, $db);
}