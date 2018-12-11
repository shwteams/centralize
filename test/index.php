<?php
    include_once '../services/lib.php';
    include_once '../services/lib.php';

    $db = DoConnexion(HOST, USER, PWD, DBNAME);
    getDatabaseTable($db);
?>