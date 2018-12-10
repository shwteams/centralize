<?php
    include '../../services/lib.php';
    include_once '../../services/parameters.php';
    $db = DoConnexion(HOST, USER, PWD, DBNAME);
if (isset($_POST["task"])) {
    $task = htmlentities($_POST["task"]);
   if ($task == "connexion") {
       
        $str_LOGIN = $_POST['str_LOGIN'];
        $str_PASSWORD = $_POST['str_PASSWORD'];
        $str_ADRESSE_IP = $_SERVER["REMOTE_ADDR"];
        $str_DESCRIPTION = $_SERVER["HTTP_USER_AGENT"];
        connexion($str_LOGIN, $str_PASSWORD, $str_ADRESSE_IP, $str_DESCRIPTION, $db);
    }
    elseif ($task == "AutoDisconnect") {
        //echo "je deconnect";
        DoAutoDeconnexion($db);
    }
}
else{
    $task = htmlentities($_GET["task"]);
   if ($task == "disconnect") {
       echo "je deconnect";
       DoDeconnexion($db);
    }
}
?>