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
    else if ($task == "getAllProduct"){
        $str_PRODUCT_ID = "";
        if(isset($_GET['str_PRODUCT_ID']))
        {
            $str_PRODUCT_ID = htmlentities($_GET['str_PRODUCT_ID']);
        }
        getAllProduct($str_PRODUCT_ID, $db);
    }
    else if ($task == "deleteProduct"){
        $str_PRODUCT_ID = $_GET["str_PRODUCT_ID"];
        deleteProduct($str_PRODUCT_ID, $db);
    }
    else if ($task == "getAllPlugged"){
        $str_PLUGGED_ID = $_GET["str_PLUGGED_ID"];
        getAllPlugged( $str_PLUGGED_ID, $db);
    }
}
else if (isset($_POST['addProduct'])){
    $str_LIBELLE = htmlentities(trim($_POST['str_LIBELLE']));
    $str_PLUGGED_ID = htmlentities(trim($_POST['str_PLUGGED_ID']));
    addProduct($db, $str_LIBELLE, $str_PLUGGED_ID);
}
else if (isset($_POST['editeProduct'])) {
    $str_LIBELLE_EDIT = htmlentities(trim($_POST['str_LIBELLE_EDIT']));
    $str_PLUGGED_EDIT_ID = htmlentities(trim($_POST['str_PLUGGED_EDIT_ID']));
    $str_PRODUCT_ID = htmlentities(trim($_POST['str_PRODUCT_ID']));
    //exit();
    editeProduct($str_LIBELLE_EDIT, $str_PLUGGED_EDIT_ID, $str_PRODUCT_ID, $db);
}