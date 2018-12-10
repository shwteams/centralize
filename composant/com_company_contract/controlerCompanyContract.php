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
    else if ($task == "getAllCompanyContract"){
        $str_RECEIPT_ID = "";
        if(isset($_GET['str_COMPANY_CONTRACT_ID']))
        {
            $str_COMPANY_CONTRACT_ID = htmlentities($_GET['str_COMPANY_CONTRACT_ID']);
        }
        getAllCompanyContract($str_COMPANY_CONTRACT_ID, $db);
    } 
    else if ($task == "deleteCompanyContract"){
        $str_COMPANY_CONTRACT_ID = $_GET["str_COMPANY_CONTRACT_ID"];
        deleteCompanyContract($str_COMPANY_CONTRACT_ID, $db);
    }
    else if ($task == "getAllContract"){
        $str_CONTRACT_ID = "";
        if(isset($_GET['str_CONTRACT_ID']))
        {
            $str_CONTRACT_ID = htmlentities($_GET['str_CONTRACT_ID']);
        }
        getAllContract($str_CONTRACT_ID, $db);
    }
} 
else if (isset($_POST['addCompanyContract'])){
    $str_RECEIPT_ID = "";
    $str_NUMERO_POLICE = htmlentities(trim($_POST['str_NUMERO_POLICE']));
    $dt_ENTREE = htmlentities(trim($_POST['dt_ENTREE']));
    $dt_SORTIE = htmlentities(trim($_POST['dt_SORTIE']));
    $str_IMMATRICULATION = htmlentities(trim($_POST['str_IMMATRICULATION']));
    $str_CODE_REGULATION = htmlentities(trim($_POST['str_CODE_REGULATION']));
    $int_NBRPLACE = htmlentities(trim($_POST['int_NBRPLACE']));
    $int_VALEUR = htmlentities(trim($_POST['int_VALEUR']));
    $int_POID = htmlentities(trim($_POST['int_POID']));
    addCompanyContract( $db, $str_NUMERO_POLICE, $dt_ENTREE, $dt_SORTIE, $str_IMMATRICULATION, $str_CODE_REGULATION, $int_NBRPLACE, $int_VALEUR, $int_POID);
}
else if (isset($_POST['editCompanyContract'])) {
    $str_NUMERO_POLICE = htmlentities(trim($_POST['str_NUMERO_POLICE']));
    $dt_ENTREE = htmlentities(trim($_POST['dt_ENTREE']));
    $dt_SORTIE = htmlentities(trim($_POST['dt_SORTIE']));
    $str_IMMATRICULATION = htmlentities(trim($_POST['str_IMMATRICULATION']));
    $str_CODE_REGULATION = htmlentities(trim($_POST['str_CODE_REGULATION']));
    $int_NBRPLACE = htmlentities(trim($_POST['int_NBRPLACE']));
    $int_VALEUR = htmlentities(trim($_POST['int_VALEUR']));
    $int_POID = htmlentities(trim($_POST['int_POID']));
    $str_CONTRAT_ENTREPRISE_ID = htmlentities(trim($_POST['str_CONTRAT_ENTREPRISE_ID']));

    editCompanyContract($str_NUMERO_POLICE, $dt_ENTREE, $dt_SORTIE, $str_IMMATRICULATION, $str_CODE_REGULATION, $int_NBRPLACE, $int_VALEUR, $int_POID, $str_CONTRAT_ENTREPRISE_ID, $db);
}