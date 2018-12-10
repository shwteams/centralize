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
    else if ($task == "getAllSinisterTreat"){
        $str_SINISTER_ID = "";
        if(isset($_GET['str_SINISTER_ID']))
        {
            $str_SINISTER_ID = htmlentities($_GET['str_SINISTER_ID']);
        }
        getAllSinisterTreat($str_SINISTER_ID, $db);
    }
    else if ($task == "deleteSinister"){
        $str_SINISTER_ID = $_GET["str_SINISTER_ID"];
        deleteSinister($str_SINISTER_ID, $db);
    }else if ($task == "getAllContrat"){
        $str_CONTRACT_ID = $_GET["str_CONTRACT_ID"];
        getAllContract($str_CONTRACT_ID, $db);
    }else if ($task == "getAllEtats"){
        $str_ETAT_ID = $_GET["str_ETAT_ID"];
        getAllEtats($str_ETAT_ID, $db);
    }else if ($task == "getAllAutreObservation"){
        $str_AUTRE_OBSERVATION_ID = "";
        if(isset($_GET['str_AUTRE_OBSERVATION_ID']))
        {
            $str_AUTRE_OBSERVATION_ID = htmlentities($_GET['str_AUTRE_OBSERVATION_ID']);
        }
        getAllAutreObservation($str_AUTRE_OBSERVATION_ID, $db);
    }
}
else if (isset($_POST['addSinister'])){
    $str_SINISTER_ID = "";
    $str_CONTRAT_ID = htmlentities(trim($_POST['str_CONTRAT_ID']));
    $dt_SURVENANCE = htmlentities(trim($_POST['dt_SURVENANCE']));
    $dt_TRAITEMENT = htmlentities(trim($_POST['dt_TRAITEMENT']));
    $mt_EVAL = htmlentities(trim($_POST['mt_EVAL']));
    $mt_CUMULE_PAYE = htmlentities(trim($_POST['mt_CUMULE_PAYE']));
    $mt_PROVISION = htmlentities(trim($_POST['mt_PROVISION']));
    //$mt_PROVISION_FIN = htmlentities(trim($_POST['mt_PROVISION_FIN']));
    addSinistre( $db, $str_CONTRAT_ID, $dt_SURVENANCE, $dt_TRAITEMENT, $mt_EVAL, $mt_CUMULE_PAYE, $mt_PROVISION, $mt_PROVISION_FIN);
}
else if (isset($_POST['editeSinister'])) {
    $str_CONTRAT_ID = htmlentities(trim($_POST['str_CONTRAT_ID']));
    $dt_SURVENANCE = htmlentities(trim($_POST['dt_SURVENANCE']));
    $dt_TRAITEMENT = htmlentities(trim($_POST['dt_TRAITEMENT']));
    $mt_EVAL = htmlentities(trim($_POST['mt_EVAL']));
    $mt_CUMULE_PAYE = htmlentities(trim($_POST['mt_CUMULE_PAYE']));
    $mt_PROVISION = htmlentities(trim($_POST['mt_PROVISION']));
    //$mt_PROVISION_FIN = htmlentities(trim($_POST['mt_PROVISION_FIN']));
    $str_SINISTER_ID = htmlentities(trim($_POST['str_SINISTER_ID']));

    editeSinistre($str_SINISTER_ID, $str_CONTRAT_ID, $dt_SURVENANCE, $dt_TRAITEMENT, $mt_EVAL, $mt_CUMULE_PAYE, $mt_PROVISION, $mt_PROVISION_FIN, $db);
}
else if (isset($_POST['editingSinister'])) {
    $str_CONTRAT_ID = htmlentities(trim($_POST['str_CONTRAT_ID']));
    $dt_SURVENANCE = htmlentities(trim($_POST['dt_SURVENANCE']));
    $dt_TRAITEMENT = htmlentities(trim($_POST['dt_TRAITEMENT']));
    $mt_EVAL = htmlentities(trim($_POST['mt_EVAL']));
    $mt_CUMULE_PAYE = htmlentities(trim($_POST['mt_CUMULE_PAYE']));
    $mt_PROVISION = htmlentities(trim($_POST['mt_PROVISION']));
    //$mt_PROVISION_FIN = htmlentities(trim($_POST['mt_PROVISION_FIN']));

    $str_SINISTER_ID = htmlentities(trim($_POST['str_SINISTER_ID']));
    $int_BONI = (int) htmlentities(trim($_POST['int_BONI']));
    $int_MALI = (int) htmlentities(trim($_POST['int_MALI']));
    $str_OBSERVATION = htmlentities(trim($_POST['str_OBSERVATION']));
    $str_AUTRE_OBSERVATION_ID = htmlentities(trim($_POST['str_AUTRE_OBSERVATION_ID']));
    $str_ETAT_ID = htmlentities(trim($_POST['str_ETAT_ID']));
    $mt_BONI_POTENTIEL = htmlentities(trim($_POST['int_BONI_POTENTIEL']));
    $str_NUMERO_SINISTRE = htmlentities(trim($_POST['str_NUMERO_SINISTRE']));

    editingSinister($str_SINISTER_ID, $str_NUMERO_SINISTRE, $str_CONTRAT_ID, $dt_SURVENANCE, $dt_TRAITEMENT, $mt_EVAL, $mt_CUMULE_PAYE, $mt_PROVISION, $mt_PROVISION_FIN, $int_BONI, $int_MALI, $str_OBSERVATION, $str_AUTRE_OBSERVATION_ID, $str_ETAT_ID, $mt_BONI_POTENTIEL, $db);
}