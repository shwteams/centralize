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
    else if ($task == "getAllUser"){
        $str_SECURITY_ID = "";
        if(isset($_GET['str_SECURITY_ID']))
        {
            $str_SECURITY_ID = htmlentities($_GET['str_SECURITY_ID']);
        }
        getAllSecurity($str_SECURITY_ID, $db);
    } 
    else if ($task == "deleteSecurity"){
        $str_SECURITY_ID = $_GET["str_SECURITY_ID"];
        deleteSecurity($str_SECURITY_ID, $db);
    }
} 
else if (isset($_POST['addSecurity'])){    
    $str_SECURITY_ID = "";
    $str_NAME = htmlentities(trim($_POST['str_NAME']));
    $str_LASTNAME = htmlentities(trim($_POST['str_LASTNAME']));
    $str_EMAIL = htmlentities(trim($_POST['str_EMAIL']));
    $str_LOGIN = htmlentities(trim($_POST['str_LOGIN']));
    $str_PASSWORD = trim($_POST['str_PASSWORD']);
    $str_PASSWORD_CONF = trim($_POST['str_PASSWORD_CONF']);
    $str_PRIVILEGE = htmlentities(trim($_POST['str_PRIVILEGE']));
    //echo $str_PRIVILEGE; exit();
    addSecurity($str_NAME, $str_LASTNAME, $str_EMAIL, $str_LOGIN, $str_PASSWORD, $str_PASSWORD_CONF,$str_PRIVILEGE, $db);
} 
else if (isset($_POST['editeSecurity'])) {
    $str_SECURITY_ID = htmlentities(trim($_POST['str_SECURITY_ID']));
    $str_NAME = htmlentities(trim($_POST['str_NAME_EDIT']));
    $str_LASTNAME = htmlentities(trim($_POST['str_LASTNAME_EDIT']));
    $str_EMAIL = htmlentities(trim($_POST['str_EMAIL_EDIT']));
    $str_LOGIN = htmlentities(trim($_POST['str_LOGIN_EDIT']));
    $str_PASSWORD = trim($_POST['str_PASSWORD_EDIT']);
    $str_PASSWORD_CONF = trim($_POST['str_PASSWORD_CONF_EDIT']);
    $str_PRIVILEGE = htmlentities(trim($_POST['str_PRIVILEGE_EDIT']));
    editSecurity($str_SECURITY_ID, $str_NAME, $str_LASTNAME, $str_EMAIL, $str_LOGIN, $str_PASSWORD, $str_PASSWORD_CONF, $str_PRIVILEGE, $db);
}