<?php
function DoConnexion($host, $SECURITY, $pass, $dbname) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        try {
            $db = new PDO($dsn, $SECURITY, $pass);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $db;
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
    }
	
	$db = DoConnexion('localhost', 'root', '', 'surveillance_portefeuille_db');
	
	function getAllSecurity($db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;

        $sql  = "SELECT * FROM t_extraction";
        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item_result) {
            $arraySql[] = $item_result;
            $intResult++;
            $code_statut = "1";
        }

        $arrayJson["results"] = $arraySql;
        $arrayJson["total"] = $intResult;
        $arrayJson["desc_statut"] = $db->errorInfo();
        $arrayJson["code_statut"] = $code_statut;
        echo json_encode($arraySql);
    }
	getAllSecurity($db);
//fetch.php


?>