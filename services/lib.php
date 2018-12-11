<?php
/**
CONTIENT TOUTES LES FONCTIONS DE MON APPLICATIONS
*/
    error_reporting(E_ALL ^ E_DEPRECATED);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    ini_set('session.gc_maxlifetime', 36000);
    //include_once('classes/PHPExcel.php');
    include_once('lib.class.php');
    //include_once('classes/PHPExcel/Reader/Excel2007.php');

    if (!isset($_SESSION)) {
	  session_start();
	}
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
    function DoDeconnexion($db) {
        //echo "deconnexion";
        addActivity($db, "deconnexion");
        session_destroy();
        header("location:../../login.php");
    }
    function DoAutoDeconnexion(){
        $arrayJson["results"] = "Deconnexion auto";
        $arrayJson["total"] = 0;
        $arrayJson["desc_statut"] = "Une erreur c'est produite !";
        $arrayJson["code_statut"] = 0;
        if(empty($_SESSION['str_SECURITY_ID'])){
            $arrayJson["results"] = "Deconnexion auto";
            $arrayJson["total"] = 0;
            $arrayJson["desc_statut"] = "Une erreur c'est produite !";
            $arrayJson["code_statut"] = 1;
        }
        echo "[" . json_encode($arrayJson) . "]";
    }
    function generatePassword($algo, $pwd) {
        $data = array();
        $data["ALGO"] = $algo;
        $data["DATE"] = $pwd;
        ksort($data);
        $message = http_build_query($data);
        $cle_bin = pack("a", KEY_PASSWORD);
        return strtoupper(hash_hmac(strtolower($data["ALGO"]), $message, $cle_bin));
    }
    function RandomString() {
        $characters = "0123456789abcdefghijklmnopqrstxwz";
        $randstring = '';
        for ($i = 0; $i < 5; $i++) {
            $randstring = $randstring . $characters[rand(0, strlen($characters))];
        }
        $unique = uniqid($randstring, "");
        return $unique;
    }
    function setUpdatePassword($db)	{
        $str_SECURITY_ID = $_SESSION['str_SECURITY_ID'];
        $str_SECURITY_ID = $db -> quote($str_SECURITY_ID);
        $arrayJson = array();
        $sql = "UPDATE t_security "
                . "SET bl_IS_UPDATE = 0 "
                . "WHERE str_SECURITY_ID = $str_SECURITY_ID;";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectué avec succès.";
                $code_statut = "1";
                $_SESSION['bl_IS_UPDATE'] = 0;
            } else {
                $message = "Erreur lors de la prise en compte de la réclamation.";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        header("location:../../index.php");
    }
    function getCountry($ip) {
        return "http://www.geoplugin.net/json.gp?ip=".$ip;
    }

    function connexion($str_LOGIN, $str_PASSWORD, $str_ADRESSE_IP, $str_DETAILS, $db) {
        sleep(2);
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "enable";
        $intResult = 0;
        $str_LOGIN = $db -> quote(htmlentities(trim($str_LOGIN)));
        $str_PASSWORD = generatePassword(ALGO, $str_PASSWORD);
        $str_PASSWORD = $db -> quote(htmlentities(trim($str_PASSWORD)));
        //var_dump($arrayJson);
        $sql = "SELECT * FROM t_security WHERE str_LOGIN LIKE " . $str_LOGIN . " AND str_PASSWORD LIKE " . $str_PASSWORD . " AND str_STATUT LIKE '" . $str_STATUT . "'";
        try{
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        catch (Exception $e){
            die($e->getMessage());
        }

        if (count($result) > 0) {
            foreach ($result as $item_result) {
                $arraySql[] = $item_result;
                $intResult++;
                $code_statut = "1";
                $_SESSION['nom'] = $item_result['str_NOM']. ' ' .$item_result['str_PRENOM'];
                $_SESSION['login'] = $item_result['str_LOGIN'];
                $_SESSION['str_SECURITY_ID'] = $item_result['str_SECURITY_ID'];
                $_SESSION['str_PRIV_ID'] = $item_result['str_PRIVILEGE'];
                $_SESSION['str_ADRESS_IP'] =  $str_ADRESSE_IP;
                $_SESSION['str_NAVIGATEUR'] = $str_DETAILS;
                addActivity($db, "connexion");
            }

            $arrayJson["results"] = "success";
            $arrayJson["total"] = $intResult;
            $arrayJson["desc_statut"] = "Connexion reussie";
            $arrayJson["code_statut"] = $code_statut;
        } else {
            $arrayJson["results"] = "le nom d'utilisateur ou le mot de passe est incorrect";
            $arrayJson["total"] = $intResult;
            $arrayJson["desc_statut"] = "Une erreur c'est produite !";
            $arrayJson["code_statut"] = $code_statut;
        }

        echo "[" . json_encode($arrayJson) . "]";
    }
    function addActivity($db, $str_STATUT){
        $str_ACTIVITY_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $payes = getCountry($_SERVER['REMOTE_ADDR']);

        $sql = "INSERT INTO t_activity(str_ACTIVITY_ID, str_LOGIN, str_NOM, str_PRIV,dt_CREATED, str_STATUT,str_SECURITY_ID, str_ADRESSE_IP,str_NAVIGATEUR,str_PAYS)"
            . "VALUES (:str_ACTIVITY_ID, :str_LOGIN, :str_NOM,:str_PRIV,$dt_CREATED,:str_STATUT,:str_SECURITY_ID,:str_ADRESSE_IP,:str_NAVIGATEUR,:str_PAYS)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->BindParam(':str_ACTIVITY_ID', $str_ACTIVITY_ID);
            $stmt->BindParam(':str_LOGIN', $_SESSION['login']);
            $stmt->BindParam(':str_NOM', $_SESSION['nom']);
            $stmt->BindParam(':str_PRIV', $_SESSION['str_PRIV_ID']);
            $stmt->BindParam(':str_STATUT', $str_STATUT);
            $stmt->BindParam(':str_SECURITY_ID', $_SESSION['str_SECURITY_ID']);
            $stmt->BindParam(':str_ADRESSE_IP', $_SESSION['str_ADRESS_IP']);
            $stmt->BindParam(':str_NAVIGATEUR', $_SESSION['str_NAVIGATEUR']);
            $stmt->BindParam(':str_PAYS', $payes);
            //var_dump($stmt);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
    }
	
    function getAllSecurity( $str_SECURITY_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_SECURITY_ID == "" || $str_SECURITY_ID == null) {
            $str_SECURITY_ID = "%%";
        }
        $str_SECURITY_ID = $db -> quote($str_SECURITY_ID);

        $sql = "SELECT * FROM t_security "
                . " WHERE str_SECURITY_ID LIKE " . $str_SECURITY_ID . " AND str_STATUT <> '".$str_STATUT."' "
                . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeSecurity($str_SECURITY_ID, $db) {
        $str_STATUT = 'delete';
        $str_SECURITY_ID = $db->quote($str_SECURITY_ID);
        $sql = "SELECT * FROM t_security WHERE str_SECURITY_ID LIKE " . $str_SECURITY_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_SECURITY_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function is_existe_use($db, $login, $mode, $str_SECURITY_ID = ""){
        $str_STATUT = 'delete';
        $login = $db->quote($login);
        if($mode === "insert"){
            $sql = "SELECT * FROM t_security WHERE str_login LIKE " . $login . " AND str_STATUT <> '" . $str_STATUT . "'";
        }
        else{
            $sql = "SELECT * FROM t_security WHERE str_login LIKE " . $login . " AND str_STATUT <> '" . $str_STATUT . "' AND str_SECURITY_ID <> '".$str_SECURITY_ID."'";
        }

        try {
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) >= 1) {
                return false;
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
            return false;
        }
        return true;
    }
    function addSecurity($str_NAME, $str_LASTNAME, $str_EMAIL, $str_LOGIN, $str_PASSWORD, $str_PASSWORD_CONF, $str_PRIVILEGE, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";

        if(is_existe_use($db, $str_LOGIN, "insert")){
            if($str_PASSWORD == $str_PASSWORD_CONF) {
                $str_SECURITY_ID = RandomString();
                $str_PASSWORD = generatePassword(ALGO, $str_PASSWORD);
                //$str_PASSWORD = $db->quote($str_PASSWORD);
                $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
                $sql = "INSERT INTO t_security(str_SECURITY_ID, str_LOGIN, str_PASSWORD, str_NOM, str_PRENOM,str_EMAIL, str_PRIVILEGE, str_STATUT, dt_CREATED, str_CREATED_BY)"
                    . "VALUES (:str_SECURITY_ID,:str_LOGIN,:str_PASSWORD,:str_NOM,:str_PRENOM,:str_EMAIL,:str_PRIVILEGE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
                try {
                    if (!isExistCodeSecurity($str_SECURITY_ID, $db)) {

                        $stmt = $db->prepare($sql);
                        $str_STATUT = "enable";
                        $stmt->BindParam(':str_SECURITY_ID', $str_SECURITY_ID);
                        $stmt->BindParam(':str_LOGIN', $str_LOGIN);
                        $stmt->BindParam(':str_PASSWORD', $str_PASSWORD);
                        $stmt->BindParam(':str_NOM', $str_NAME);
                        $stmt->BindParam(':str_PRENOM', $str_LASTNAME);
                        $stmt->BindParam(':str_EMAIL', $str_EMAIL);
                        $stmt->BindParam(':str_PRIVILEGE', $str_PRIVILEGE);
                        $stmt->BindParam(':str_STATUT', $str_STATUT);
                        $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                        //var_dump($stmt);
                        if ($stmt->execute()) {
                            $message = "Insertion effectué avec succès";
                            $code_statut = "1";
                        } else {
                            $message = "Erreur lors de l'insertion";
                            $code_statut = "0";
                        }
                    } else {
                        $message = "Ce Code  : \" " . $str_SECURITY_ID . " \" de table existe déja! \r\n";
                        $code_statut = "0";
                    }
                } catch (PDOException $e) {
                    die("Erreur ! : " . $e->getMessage());
                }
            } else {
                $message = "Les mots de passe sont identique.";
                $code_statut = "0";
            }
        }
        else{
            $message = "Ce nom d'utilisateur est déjà utilisé.";
            $code_statut = "0";
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteSecurity($str_SECURITY_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_SECURITY_ID = $db->quote($str_SECURITY_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_security "
                . "SET str_STATUT = '$str_STATUT',"
                . "str_UPDATED_BY = $str_UPDATED_BY, "
                . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
                . "WHERE str_SECURITY_ID = $str_SECURITY_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editSecurity($str_SECURITY_ID, $str_NAME, $str_LASTNAME, $str_EMAIL, $str_LOGIN, $str_PASSWORD, $str_PASSWORD_CONF,$str_PRIVILEGE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        if(is_existe_use($db, $str_LOGIN, "edit", $str_SECURITY_ID)){
            $str_SECURITY_ID = $db->quote($str_SECURITY_ID);
            $str_NAME = $db->quote($str_NAME);
            $str_LASTNAME=$db->quote($str_LASTNAME);
            $str_LOGIN=$db->quote($str_LOGIN);
            $str_EMAIL=$db->quote($str_EMAIL);
            $str_PRIVILEGE=$db->quote($str_PRIVILEGE);
            if ($str_PASSWORD_CONF === $str_PASSWORD){
                $str_PASSWORD = generatePassword(ALGO, $str_PASSWORD);
                $str_PASSWORD = $db->quote($str_PASSWORD);
                //$str_ILLUSTRATION = NULL;
                $sql = "UPDATE t_security "
                    . "SET str_NOM = $str_NAME,"
                    . "str_PRENOM = $str_LASTNAME,"
                    . "str_LOGIN = $str_LOGIN,"
                    . "str_PASSWORD = $str_PASSWORD,"
                    . "str_EMAIL = $str_EMAIL,"
                    . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
                    . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "',"
                    . "str_PRIVILEGE = $str_PRIVILEGE"
                    . " WHERE str_SECURITY_ID = $str_SECURITY_ID";

                try {
                    $sucess = $db->exec($sql);
                    if ($sucess > 0) {
                        $message = "Modification effectuée avec succès";
                        $code_statut = "1";
                    } else {
                        $message = "Erreur lors de la modification";
                        $code_statut = "0";
                    }
                } catch (PDOException $e) {
                    die("Erreur ! : " . $e->getMessage());
                }
            }
            else {
                $message = "Les mots de passe ne sont pas identique.";
                $code_statut = "0";
            }
        }
        else{
            $message = "Le login est déjà utilisé.";
            $code_statut = "0";
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
    //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
	
	function getAllEtats( $str_ETAT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_ETAT_ID == "" || $str_ETAT_ID == null) {
            $str_ETAT_ID = "%%";
        }
        $str_ETAT_ID = $db -> quote($str_ETAT_ID);

        $sql = "SELECT * FROM t_etat "
            . " WHERE str_ETAT_ID LIKE " . $str_ETAT_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeEtats($str_ETAT_ID, $db) {
        $str_STATUT = 'delete';
        $str_ETAT_ID = $db->quote($str_ETAT_ID);
        $sql = "SELECT * FROM t_etat WHERE str_ETAT_ID LIKE " . $str_ETAT_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_ETAT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function getAllEtat( $str_ETAT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_ETAT_ID == "" || $str_ETAT_ID == null) {
            $str_ETAT_ID = "%%";
        }
        $str_ETAT_ID = $db -> quote($str_ETAT_ID);

        $sql = "SELECT * FROM t_etat "
            . " WHERE str_ETAT_ID LIKE " . $str_ETAT_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function addEtats( $db, $str_LIBELLE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_ETAT_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_etat(str_ETAT_ID, str_LIBELLE, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_ETAT_ID, :str_LIBELLE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistCodeEtats($str_ETAT_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_ETAT_ID', $str_ETAT_ID);
                $stmt->BindParam(':str_LIBELLE', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_ETAT_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteEtats($str_ETAT_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_ETAT_ID = $db->quote($str_ETAT_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_etat "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_ETAT_ID = $str_ETAT_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editEtats($str_ETAT_ID, $str_LIBELLE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
//ECHO $str_ETAT_ID;
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_ETAT_ID = $db->quote($str_ETAT_ID);
        $sql = "UPDATE t_etat "
            . "SET str_LIBELLE = $str_LIBELLE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_ETAT_ID = $str_ETAT_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
	function getAllAutreObservation( $str_AUTRE_OBSERVATION_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_AUTRE_OBSERVATION_ID == "" || $str_AUTRE_OBSERVATION_ID == null) {
            $str_AUTRE_OBSERVATION_ID = "%%";
        }
        $str_AUTRE_OBSERVATION_ID = $db -> quote($str_AUTRE_OBSERVATION_ID);

        $sql = "SELECT * FROM t_autre_observation "
            . " WHERE str_AUTRE_OBSERVATION_ID LIKE " . $str_AUTRE_OBSERVATION_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeAutreObservation($str_AUTRE_OBSERVATION_ID, $db) {
        $str_STATUT = 'delete';
        $str_AUTRE_OBSERVATION_ID = $db->quote($str_AUTRE_OBSERVATION_ID);
        $sql = "SELECT * FROM t_autre_observation WHERE str_AUTRE_OBSERVATION_ID LIKE " . $str_AUTRE_OBSERVATION_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_AUTRE_OBSERVATION_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addAutreObservation( $db, $str_LIBELLE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_AUTRE_OBSERVATION_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_autre_observation(str_AUTRE_OBSERVATION_ID, str_LIBELLE, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_AUTRE_OBSERVATION_ID, :str_LIBELLE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistCodeAutreObservation($str_AUTRE_OBSERVATION_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_AUTRE_OBSERVATION_ID', $str_AUTRE_OBSERVATION_ID);
                $stmt->BindParam(':str_LIBELLE', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_AUTRE_OBSERVATION_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteAutreObservation($str_AUTRE_OBSERVATION_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_AUTRE_OBSERVATION_ID = $db->quote($str_AUTRE_OBSERVATION_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_autre_observation "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_AUTRE_OBSERVATION_ID = $str_AUTRE_OBSERVATION_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editAutreObservation($str_AUTRE_OBSERVATION_ID, $str_LIBELLE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_AUTRE_OBSERVATION_ID = $db->quote($str_AUTRE_OBSERVATION_ID);
        $sql = "UPDATE t_autre_observation "
            . "SET str_LIBELLE = $str_LIBELLE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_AUTRE_OBSERVATION_ID = $str_AUTRE_OBSERVATION_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' et '; //' et ';
        $separator   = ', ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'un',
            2                   => 'deux',
            3                   => 'trois',
            4                   => 'quatre',
            5                   => 'cinq',
            6                   => 'six',
            7                   => 'sept',
            8                   => 'huit',
            9                   => 'neuf',
            10                  => 'dix',
            11                  => 'onze',
            12                  => 'douze',
            13                  => 'treize',
            14                  => 'quatorze',
            15                  => 'quinze',
            16                  => 'seize',
            17                  => 'dix sept',
            18                  => 'dix huit',
            19                  => 'dix neuf',
            20                  => 'vingt',
            30                  => 'trente',
            40                  => 'quarante',
            50                  => 'cinquante',
            60                  => 'soixante',
            70                  => 'soixante-dix',
            80                  => 'quatre-vingt',
            90                  => 'quatre-vingt dix',
            100                 => 'cent',
            1000                => 'mille',
            1000000             => 'million',
            1000000000          => 'milliard',
            1000000000000       => 'billion',
            1000000000000000    => 'billiard'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 17:
                $string = $dictionary[$number];
                break;
            case $number < 20:
                $string = $dictionary[10];
                $un = $number - 10;
                $string .= $hyphen . $dictionary[$un];
                break;
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                if (($tens == 70) || ($tens == 90))
                {
                    $un = $tens-10;
                    $string = $dictionary[$un];
                    $units+=10;
                }
                else
                {
                    $string = $dictionary[$tens];
                }
                if ($units) {
                    if ($units == 1)
                        $string .= $conjunction . $dictionary[$units];
                    else
                        $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                if ($hundreds >= 2)
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                else
                    $string = $dictionary[100];
                if ($remainder) {
                    $string .=' '. convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= ' ';//$remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }
        return $string;
    }
    function soundex_fr($mot)
    {
        mb_internal_encoding ("utf-8");
    // On peut mettre toutes les lettres qu'on veut ignorer à la fin. Il suffit qu'elles n(='aient pas d'éqivalent de même rang dans $nombres
        $lettres = array (
            "b",
            "p" ,
            "c",
            "k",
            "q",
            "d",
            "t",
            "l",
            "m",
            "n",
            "r",
            "g",
            "j",
            "x",
            "z",
            "s",
            "f",
            "v",
            "a",
            "â",
            "à",
            "ä",
            "e",
            "é",
            "è",
            "ë",
            "ë",
            "i",
            "î",
            "i",
            "o",
            "ô",
            "ö",
            "u",
            "û",
            "ü",
            "y",
        );

        /*rien n'interdit de remplacer les nombres par des symboles (il n'est pas fait d'opérations arithmétiques sur ces valeurs) et de diversifier, par exemple, en rempalçant l'équivalent du "s" par un symbole.*/
        $nombres = array (
            1,
            1,
            2,
            2,
            2,
            3,
            3,
            4,
            5,
            5,
            6,
            7,
            7,
            8,
            8,
            8,
            9,
            9,
        );

        $mot = mb_strtolower(trim($mot));

        //print "$mot<br>";
        $sound = str_replace ($lettres, $nombres, $mot);
        return $sound;
    }
    function getLibenshteinConstante($mot1, $mot2)
    {
        $phon1 = soundex_fr($mot1);
        $phon2 = soundex_fr($mot2);
        //var_dump($phon1,$phon2);
        $pourcentage = levenshtein($phon1,$phon2)*100/strlen($mot1);
        if ($pourcentage<=25)//($pourcentage<=25)
        {
            return $mot2;
        }
    }
    function getAlphabetiqueWord(){
        Lib::getXLSrow();
    }
    function getAllPartner( $str_PARTNER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_PARTNER_ID == "" || $str_PARTNER_ID == null) {
            $str_PARTNER_ID = "%%";
        }
        $str_PARTNER_ID = $db -> quote($str_PARTNER_ID);

        $sql = "SELECT * FROM t_partenaire "
            . " WHERE str_PARTENAIRE_ID LIKE " . $str_PARTNER_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodePartner($str_PARTNER_ID, $db) {
        $str_STATUT = 'delete';
        $str_PARTNER_ID = $db->quote($str_PARTNER_ID);
        $sql = "SELECT * FROM t_partenaire WHERE str_PARTENAIRE_ID LIKE " . $str_PARTNER_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_PARTNER_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addPartner( $db, $str_LIBELLE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_PARTENAIRE_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_partenaire(str_PARTENAIRE_ID, str_LIBELLE, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_PARTENAIRE_ID, :str_LIBELLE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistCodePartner($str_PARTENAIRE_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_PARTENAIRE_ID', $str_PARTENAIRE_ID);
                $stmt->BindParam(':str_LIBELLE', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_PARTENAIRE_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deletePartner($str_PARTENAIRE_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_PARTENAIRE_ID = $db->quote($str_PARTENAIRE_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_partenaire "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_PARTENAIRE_ID = $str_PARTENAIRE_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editePartner($str_PARTENAIRE_ID, $str_LIBELLE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_PARTENAIRE_ID = $db->quote($str_PARTENAIRE_ID);
        $sql = "UPDATE t_partenaire "
            . "SET str_LIBELLE = $str_LIBELLE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_PARTENAIRE_ID = $str_PARTENAIRE_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllPlugged( $str_PLUGGED_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_PLUGGED_ID == "" || $str_PLUGGED_ID == null) {
            $str_PLUGGED_ID = "%%";
        }
        $str_PLUGGED_ID = $db -> quote($str_PLUGGED_ID);

        $sql = "SELECT * FROM t_branche "
            . " WHERE str_BRANCHE_ID LIKE " . $str_PLUGGED_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodePlugged($str_PLUGGED_ID, $db) {
        $str_STATUT = 'delete';
        $str_PLUGGED_ID = $db->quote($str_PLUGGED_ID);
        $sql = "SELECT * FROM t_branche WHERE str_BRANCHE_ID LIKE " . $str_PLUGGED_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_PLUGGED_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addPlugged( $db, $str_LIBELLE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_PARTENAIRE_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_branche(str_BRANCHE_ID, str_LIBELLE, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_BRANCHE_ID, :str_LIBELLE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistCodePartner($str_PARTENAIRE_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_BRANCHE_ID', $str_PARTENAIRE_ID);
                $stmt->BindParam(':str_LIBELLE', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_PARTENAIRE_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deletePlugged($str_PLUGGED_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_PLUGGED_ID = $db->quote($str_PLUGGED_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_branche "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_BRANCHE_ID = $str_PLUGGED_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editePlugged($str_PLUGGED_ID, $str_LIBELLE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_PLUGGED_ID = $db->quote($str_PLUGGED_ID);
        $sql = "UPDATE t_branche "
            . "SET str_LIBELLE = $str_LIBELLE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_BRANCHE_ID = $str_PLUGGED_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllReinsurer( $str_REINSURER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_REINSURER_ID == "" || $str_REINSURER_ID == null) {
            $str_REINSURER_ID = "%%";
        }
        $str_REINSURER_ID = $db -> quote($str_REINSURER_ID);
        $sql = "SELECT * FROM t_reassureur "
            . " WHERE str_REASSUREUR_ID LIKE " . $str_REINSURER_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeReinsurer($str_REINSURER_ID, $db) {
        $str_STATUT = 'delete';
        $str_REINSURER_ID = $db->quote($str_REINSURER_ID);
        $sql = "SELECT * FROM t_reassureur WHERE str_REASSUREUR_ID LIKE " . $str_REINSURER_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_REINSURER_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addReinsurer( $db, $str_LIBELLE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_REINSURER = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_reassureur(str_REASSUREUR_ID, str_NOM, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_REASSUREUR_ID, :str_NOM, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistCodeReinsurer($str_REINSURER, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_REASSUREUR_ID', $str_REINSURER);
                $stmt->BindParam(':str_NOM', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_REINSURER . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteReinsurer($str_REINSURER_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_REINSURER_ID = $db->quote($str_REINSURER_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_reassureur "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_REASSUREUR_ID = $str_REINSURER_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editeReinsurer($str_REINSURER, $str_LIBELLE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_REINSURER = $db->quote($str_REINSURER);
        $sql = "UPDATE t_reassureur "
            . "SET str_NOM = $str_LIBELLE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_REASSUREUR_ID = $str_REINSURER";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllIntermediate( $str_INTERMEDIATE, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_INTERMEDIATE == "" || $str_INTERMEDIATE == null) {
            $str_INTERMEDIATE = "%%";
        }
        $str_INTERMEDIATE = $db -> quote($str_INTERMEDIATE);
        $sql = "SELECT * FROM t_intermediaire "
            . " WHERE str_INTERMEDIAIRE_ID LIKE " . $str_INTERMEDIATE . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistIntermediate($str_INTERMEDIATE_ID, $db) {
        $str_STATUT = 'delete';
        $str_INTERMEDIATE_ID = $db->quote($str_INTERMEDIATE_ID);
        $sql = "SELECT * FROM t_intermediaire WHERE str_INTERMEDIAIRE_ID LIKE " . $str_INTERMEDIATE_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_INTERMEDIATE_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addIntermediate( $db, $str_LIBELLE, $str_CODE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_INTERMEDIAIRE_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_intermediaire(str_INTERMEDIAIRE_ID, str_NOM, str_CODE, str_STATUT, dt_CREATED, str_CREATED_BY)"
            . "VALUES (:str_INTERMEDIAIRE_ID, :str_NOM, :str_CODE, :str_STATUT,$dt_CREATED,:str_CREATED_BY)";
        try {
            if (!isExistIntermediate($str_INTERMEDIAIRE_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_INTERMEDIAIRE_ID', $str_INTERMEDIAIRE_ID);
                $stmt->BindParam(':str_NOM', $str_LIBELLE);
                $stmt->BindParam(':str_CODE', $str_CODE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_INTERMEDIAIRE_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteIntermediate($str_INTERMEDIAIRE_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_INTERMEDIAIRE_ID = $db->quote($str_INTERMEDIAIRE_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_intermediaire "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_INTERMEDIAIRE_ID = $str_INTERMEDIAIRE_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editeIntermediate($str_INTERMEDIAIRE_ID, $str_LIBELLE, $str_CODE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $str_CODE = $db->quote($str_CODE);
        $str_INTERMEDIAIRE_ID = $db->quote($str_INTERMEDIAIRE_ID);
        $sql = "UPDATE t_intermediaire "
            . "SET str_NOM = $str_LIBELLE,"
            . " str_CODE = $str_CODE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_INTERMEDIAIRE_ID = $str_INTERMEDIAIRE_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }


    function getCustomerByName( $str_NAME, $db) {
        $outPut = '';
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        //$str_NAME = $db -> quote($str_NAME);
        $sql = "SELECT str_NOM FROM t_client WHERE str_STATUT <> '{$str_STATUT}' ";
        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $outPut = '<ul class="list-unstyled">';

        foreach ($result as $item_result) {
            $data = getLibenshteinConstante($str_NAME, $item_result['str_NOM']);
            if($data != NULL or $data != "")
            {
                $item_result = array('str_NOM' => $data);

                $arraySql[] = $item_result;
                $outPut .= "<li>".$item_result['str_NOM']."</li>";
                $intResult++;
                $code_statut = "1";
            }
        }
        if($code_statut == 0)
        {
            $outPut .= "<li>le nom recherché n'existe pas</li>";
        }
        else{
            $outPut .= "</ul>";
        }
        $arrayJson["results"] = $arraySql;
        $arrayJson["total"] = $intResult;
        $arrayJson["desc_statut"] = $db->errorInfo();
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllCustomer( $str_CUSTOMER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_CUSTOMER_ID == "" || $str_CUSTOMER_ID == null) {
            $str_CUSTOMER_ID = "%%";
        }
        $str_CUSTOMER_ID = $db -> quote($str_CUSTOMER_ID);
        $sql = "SELECT str_CLIENT_ID, str_NOM, t_client.str_PARTENAIRE_ID, str_LIBELLE FROM t_client "
            . " LEFT JOIN t_partenaire ON t_partenaire.str_PARTENAIRE_ID = t_client.str_PARTENAIRE_ID "
            . " WHERE str_CLIENT_ID LIKE " . $str_CUSTOMER_ID . " AND t_client.str_STATUT <> '".$str_STATUT."' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCustomer($str_CUSTOMER_ID, $db) {
        $str_STATUT = 'delete';
        $str_CUSTOMER_ID = $db->quote($str_CUSTOMER_ID);
        $sql = "SELECT * FROM t_client WHERE str_CLIENT_ID LIKE " . $str_CUSTOMER_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_CUSTOMER_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addCustomer($db, $str_LIBELLE, $str_PARTNER_ID) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_CUSTOMER_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_client(str_CLIENT_ID, str_NOM, str_STATUT, dt_CREATED, str_CREATED_BY, str_PARTENAIRE_ID)"
            . "VALUES (:str_CLIENT_ID, :str_NOM, :str_STATUT,$dt_CREATED,:str_CREATED_BY, :str_PARTENAIRE_ID)";
        try {
            if (!isExistCustomer($str_CUSTOMER_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_CLIENT_ID', $str_CUSTOMER_ID);
                $stmt->BindParam(':str_NOM', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                $stmt->BindParam(':str_PARTENAIRE_ID', $str_PARTNER_ID);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_CUSTOMER_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteCustomer($str_CUSTOMER_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_CUSTOMER_ID = $db->quote($str_CUSTOMER_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_client "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_CLIENT_ID = $str_CUSTOMER_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editeCustomer($str_PARTNER_EDIT_ID, $str_LIBELLE, $str_CUSTOMER_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_PARTNER_EDIT_ID = $db->quote($str_PARTNER_EDIT_ID);
        $str_CUSTOMER_ID = $db->quote($str_CUSTOMER_ID);
        $str_LIBELLE = $db->quote($str_LIBELLE);
        $sql = "UPDATE t_client "
            . "SET str_NOM = $str_LIBELLE,"
            . " str_PARTENAIRE_ID = $str_PARTNER_EDIT_ID,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_CLIENT_ID = $str_CUSTOMER_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllProduct( $str_PRODUIT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_PRODUIT_ID == "" || $str_PRODUIT_ID == null) {
            $str_PRODUIT_ID = "%%";
        }
        $str_PRODUIT_ID = $db -> quote($str_PRODUIT_ID);
        $sql = "SELECT str_PRODUIT_ID, t_produit.str_LIBELLE, t_branche.str_BRANCHE_ID, t_branche.str_LIBELLE AS str_LIBELLE_BRANCHE "
            .  " FROM t_produit "
            . " LEFT JOIN t_branche ON t_branche.str_BRANCHE_ID = t_produit.str_BRANCHE_ID "
            . " WHERE str_PRODUIT_ID LIKE " . $str_PRODUIT_ID . " AND t_produit.str_STATUT <> '".$str_STATUT."' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistProduct($str_PRODUCT_ID, $db) {
        $str_STATUT = 'delete';
        $str_PRODUCT_ID = $db->quote($str_PRODUCT_ID);
        $sql = "SELECT * FROM t_produit WHERE str_PRODUIT_ID LIKE " . $str_PRODUCT_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_PRODUCT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addProduct($db, $str_LIBELLE, $str_BRANCHE_ID) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_PRODUIT_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_produit(str_PRODUIT_ID, str_LIBELLE, str_STATUT, dt_CREATED, str_CREATED_BY, str_BRANCHE_ID)"
            . "VALUES (:str_PRODUIT_ID, :str_LIBELLE, :str_STATUT,$dt_CREATED,:str_CREATED_BY, :str_BRANCHE_ID)";
        try {
            if (!isExistProduct($str_PRODUIT_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_PRODUIT_ID', $str_PRODUIT_ID);
                $stmt->BindParam(':str_LIBELLE', $str_LIBELLE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                $stmt->BindParam(':str_BRANCHE_ID', $str_BRANCHE_ID);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_PRODUIT_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteProduct($str_PRODUCT_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_PRODUCT_ID = $db->quote($str_PRODUCT_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_produit "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_PRODUIT_ID = $str_PRODUCT_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editeProduct($str_LIBELLE_EDIT, $str_PLUGGED_EDIT_ID, $str_PRODUCT_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_LIBELLE_EDIT = $db->quote($str_LIBELLE_EDIT);
        $str_PLUGGED_EDIT_ID = $db->quote($str_PLUGGED_EDIT_ID);
        $str_PRODUCT_ID = $db->quote($str_PRODUCT_ID);
        $sql = "UPDATE t_produit "
            . "SET str_LIBELLE = $str_LIBELLE_EDIT,"
            . " str_BRANCHE_ID = $str_PLUGGED_EDIT_ID,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_PRODUIT_ID = $str_PRODUCT_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllProductByPlugged( $str_BRANCHE_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_BRANCHE_ID == "" || $str_BRANCHE_ID == null) {
            $str_BRANCHE_ID = "%%";
        }
        $str_BRANCHE_ID = $db -> quote($str_BRANCHE_ID);
        $sql = "SELECT str_PRODUIT_ID, t_produit.str_LIBELLE, t_branche.str_BRANCHE_ID, t_branche.str_LIBELLE AS str_LIBELLE_BRANCHE "
            .  " FROM t_produit "
            . " LEFT JOIN t_branche ON t_branche.str_BRANCHE_ID = t_produit.str_BRANCHE_ID "
            . " WHERE t_branche.str_BRANCHE_ID LIKE " . $str_BRANCHE_ID . " AND t_branche.str_STATUT <> '".$str_STATUT."' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function addContract($db, $str_CONTRACT_ID, $str_PRODUIT_ID, $str_ETAT, $str_POLICENUMBER, $str_MOTIF, $int_CARNUMBER, $str_CUSTOMER_ID) {

        if($str_ETAT=='on')
        {
            $str_ETAT=1;
        }
        else{
            $str_ETAT=0;
        }
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_CONTRACT_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_contrat(str_CONTRAT_ID, str_ETAT, str_NUMERO_POLICE, str_MOTIF, int_NOMBRE_VEHICULE, str_STATUT, dt_CREATED, str_CREATED_BY, str_CLIENT_ID, str_PRODUIT_ID)"
            . "VALUES (:str_CONTRAT_ID, :str_ETAT, :str_NUMERO_POLICE, :str_MOTIF, :int_NOMBRE_VEHICULE, :str_STATUT, $dt_CREATED, :str_CREATED_BY, :str_CLIENT_ID, :str_PRODUIT_ID)";
        try {
            if (!isExistContract($str_CONTRACT_ID, $db)) {
                if(!isExistNumeroPolice($str_POLICENUMBER, $db)){
                    $stmt = $db->prepare($sql);
                    $stmt->BindParam(':str_CONTRAT_ID', $str_CONTRACT_ID);
                    $stmt->BindParam(':str_ETAT', $str_ETAT);
                    $stmt->BindParam(':str_NUMERO_POLICE', $str_POLICENUMBER);
                    $stmt->BindParam(':str_MOTIF', $str_MOTIF);
                    $stmt->BindParam(':int_NOMBRE_VEHICULE', $int_CARNUMBER);
                    $stmt->BindParam(':str_STATUT', $str_STATUT);
                    $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                    $stmt->BindParam(':str_CLIENT_ID', $str_CUSTOMER_ID);
                    $stmt->BindParam(':str_PRODUIT_ID', $str_PRODUIT_ID);
                    //var_dump($stmt);
                    if ($stmt->execute()) {
                        $message = "Insertion effectué avec succès";
                        $code_statut = "1";
                    } else {
                        $message = "Erreur lors de l'insertion";
                        $code_statut = "0";
                    }
                }
                else{
                    $message = "Ce numéro de police  : \" " . $str_POLICENUMBER . " \" existe déja! \r\n";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_CONTRACT_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistNumeroPolice($str_POLICE_NUMBER, $db) {
        $str_STATUT = 'delete';
        $str_POLICE_NUMBER = $db->quote($str_POLICE_NUMBER);
        $sql = "SELECT * FROM t_contrat WHERE str_NUMERO_POLICE LIKE " . $str_POLICE_NUMBER . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_POLICE_NUMBER)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function isExistNumeroPoliceForUpdate($str_POLICE_NUMBER, $str_CONTRAT_ID, $db) {
        $str_STATUT = 'delete';
        /*$str_POLICE_NUMBER = $db->quote($str_POLICE_NUMBER);
        $str_CONTRAT_ID = $db->quote($str_CONTRAT_ID);*/
        $sql = "SELECT * FROM t_contrat WHERE str_NUMERO_POLICE LIKE " . $str_POLICE_NUMBER . " AND str_STATUT <> '" . $str_STATUT . "' AND str_CONTRAT_ID <> $str_CONTRAT_ID";
        if(!empty($str_CONTRAT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function isExistContract($str_CONTRACT_ID, $db) {
        $str_STATUT = 'delete';
        $str_CONTRACT_ID = $db->quote($str_CONTRACT_ID);
        $sql = "SELECT * FROM t_contrat WHERE str_CONTRAT_ID LIKE " . $str_CONTRACT_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_CONTRACT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function getAllContract( $str_CONTRACT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_CONTRACT_ID == "" || $str_CONTRACT_ID == null) {
            $str_CONTRACT_ID = "%%";
        }
        $str_CONTRACT_ID = $db -> quote($str_CONTRACT_ID);
        $sql = "SELECT t_contrat.*, t_produit.str_LIBELLE, t_branche.str_BRANCHE_ID, t_branche.str_LIBELLE AS str_LIBELLE_BRANCHE, t_client.str_NOM
                        FROM t_contrat 
                        JOIN t_produit ON t_contrat.str_PRODUIT_ID = t_produit.str_PRODUIT_ID
                        JOIN t_branche ON t_branche.str_BRANCHE_ID = t_produit.str_BRANCHE_ID 
                        JOIN t_client ON t_contrat.str_CLIENT_ID = t_client.str_CLIENT_ID 
                        WHERE str_CONTRAT_ID LIKE ".$str_CONTRACT_ID." AND t_contrat.str_STATUT <> 'delete' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteContract($str_CONTRACT_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_CONTRACT_ID = $db->quote($str_CONTRACT_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
         $sql = "UPDATE t_contrat "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_CONTRAT_ID = $str_CONTRACT_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }

    function editeContract($db, $str_CONTRACT_EDIT_ID, $str_PRODUIT_ID, $str_ETAT, $str_POLICENUMBER, $str_MOTIF, $int_CARNUMBER, $str_CUSTOMER_ID)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        if ($str_ETAT == 'on') {
            $str_ETAT = 1;
        } else {
            $str_ETAT = 0;
        }
        $str_CONTRACT_EDIT_ID = $db->quote($str_CONTRACT_EDIT_ID);
        $str_PRODUIT_ID = $db->quote($str_PRODUIT_ID);
        $str_ETAT = $db->quote($str_ETAT);
        $str_POLICENUMBER = $db->quote($str_POLICENUMBER);
        $str_MOTIF = $db->quote($str_MOTIF);
        if ($int_CARNUMBER <> NULL) {
            $int_CARNUMBER = $db->quote($int_CARNUMBER);
        } else {
            $int_CARNUMBER = 'NULL';
        }

        $str_CUSTOMER_ID = $db->quote($str_CUSTOMER_ID);
        if (!isExistNumeroPoliceForUpdate($str_POLICENUMBER, $str_CONTRACT_EDIT_ID, $db)) {
            $sql = "UPDATE t_contrat "
                . "SET str_ETAT = $str_ETAT,"
                . " str_NUMERO_POLICE = $str_POLICENUMBER,"
                . " str_MOTIF = $str_MOTIF,"
                . ($int_CARNUMBER <> NULL ? " int_NOMBRE_VEHICULE = $int_CARNUMBER," : "")
                . " str_CLIENT_ID = $str_CUSTOMER_ID,"
                . " str_PRODUIT_ID = $str_PRODUIT_ID,"
                . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
                . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
                . " WHERE str_CONTRAT_ID = $str_CONTRACT_EDIT_ID";

            try {
                $sucess = $db->exec($sql);
                if ($sucess > 0) {
                    $message = "Modification effectuée avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de la modification";
                    $code_statut = "0";
                }
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
            }
        }
        else
        {
            $message = "Ce numéro de police : $str_POLICENUMBER est déjà utilisé!";
            $code_statut = "0";
            $sucess = 0;
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllSinister( $str_SINISTER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_SINISTER_ID == "" || $str_SINISTER_ID == null) {
            $str_SINISTER_ID = "%%";
        }
        $str_SINISTER_ID = $db -> quote($str_SINISTER_ID);
        $sql = "SELECT t_sinistre.*, t_contrat.str_NUMERO_POLICE "
            .  " FROM t_sinistre "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_sinistre.str_CONTRAT_ID "
            . " WHERE t_sinistre.str_SINISTRE_ID LIKE " . $str_SINISTER_ID . " AND t_sinistre.str_STATUT <> '".$str_STATUT."' AND t_sinistre.str_STATUT LIKE 'enable' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }

    function addSinistre( $db, $str_NUMERO_SINISTRE, $str_CONTRAT_ID, $dt_SURVENANCE, $dt_TRAITEMENT, $mt_EVAL, $mt_PROVISION) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_SINISTRE_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_sinistre(str_SINISTRE_ID, str_NUMERO_SINISTRE, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_PROVISION, str_STATUT, str_CREATED_BY, dt_CREATED, str_CONTRAT_ID) "
            . "VALUES (:str_SINISTRE_ID, :str_NUMERO_SINISTRE, :dt_SURVENANCE, :dt_TRAITEMENT, :mt_EVAL, :mt_PROVISION, :str_STATUT, :str_CREATED_BY, $dt_CREATED, :str_CONTRAT_ID)";
        try {
            if (!isExistCodeSinister($str_SINISTRE_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_SINISTRE_ID', $str_SINISTRE_ID);
                $stmt->BindParam(':str_NUMERO_SINISTRE', $str_NUMERO_SINISTRE);
                $stmt->BindParam(':dt_SURVENANCE', $dt_SURVENANCE);
                $stmt->BindParam(':dt_TRAITEMENT', $dt_TRAITEMENT);
                $stmt->BindParam(':mt_EVAL', $mt_EVAL);
                //$stmt->BindParam(':mt_CUMULE_PAYE', $mt_CUMULE_PAYE);
                $stmt->BindParam(':mt_PROVISION', $mt_PROVISION);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                $stmt->BindParam(':str_CONTRAT_ID', $str_CONTRAT_ID);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_SINISTRE_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeSinister($str_SINISTER_ID, $db) {
        $str_STATUT = 'delete';
        $str_SINISTER_ID = $db->quote($str_SINISTER_ID);
        $sql = "SELECT * FROM t_sinistre WHERE str_SINISTRE_ID LIKE " . $str_SINISTER_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_SINISTER_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function deleteSinister($str_SINISTER_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_SINISTER_ID = $db->quote($str_SINISTER_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_sinistre "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . " WHERE str_SINISTRE_ID = $str_SINISTER_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editeSinistre($str_SINISTER_ID, $str_NUMERO_SINISTRE, $str_CONTRAT_ID, $dt_SURVENANCE, $dt_TRAITEMENT, $mt_EVAL, $mt_PROVISION, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";

        $str_SINISTER_ID = $db->quote($str_SINISTER_ID);
        $str_NUMERO_SINISTRE = $db->quote($str_NUMERO_SINISTRE);
        $str_CONTRAT_ID = $db->quote($str_CONTRAT_ID);
        $dt_SURVENANCE = $db->quote($dt_SURVENANCE);
        $dt_TRAITEMENT = $db->quote($dt_TRAITEMENT);
        $mt_EVAL = $db->quote($mt_EVAL);
        $mt_PROVISION = $db->quote($mt_PROVISION);
        $sql = "UPDATE t_sinistre "
            . "SET mt_EVAL = $mt_EVAL,"
            . " str_NUMERO_SINISTRE = $str_NUMERO_SINISTRE,"
            . " dt_SURVENANCE = $dt_SURVENANCE,"
            . " dt_TRAITEMENT = $dt_TRAITEMENT,"
            . " mt_PROVISION = $mt_PROVISION,"
            . " str_CONTRAT_ID = $str_CONTRAT_ID,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_SINISTRE_ID = $str_SINISTER_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editingSinister($str_SINISTER_ID, $str_NUMERO_SINISTRE, $str_CONTRAT_ID, $mt_EVAL, $mt_CUMULE_PAYE, $mt_PROVISION, $int_BONI, $int_MALI, $str_OBSERVATION, $str_AUTRE_OBSERVATION_ID, $str_ETAT_ID, $mt_BONI_POTENTIEL, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "'edite'";

        $str_SINISTER_ID = $db->quote($str_SINISTER_ID);
        $str_CONTRAT_ID = $db->quote($str_CONTRAT_ID);
        $str_NUMERO_SINISTRE = $db->quote($str_NUMERO_SINISTRE);
        $mt_EVAL = $db->quote($mt_EVAL);
        $mt_CUMULE_PAYE = $db->quote($mt_CUMULE_PAYE);
        $mt_PROVISION = $db->quote($mt_PROVISION);
        $mt_BONI_POTENTIEL = $db->quote($mt_BONI_POTENTIEL);
        $int_BONI = $db->quote($int_BONI);
        $int_MALI = $db->quote($int_MALI);
        $str_OBSERVATION = $db->quote($str_OBSERVATION);
        $str_AUTRE_OBSERVATION_ID = $db->quote($str_AUTRE_OBSERVATION_ID);
        $str_ETAT_ID = $db->quote($str_ETAT_ID);

        $sql = "UPDATE t_sinistre "
            . "SET mt_EVAL = $mt_EVAL,"
            . " str_NUMERO_SINISTRE = $str_NUMERO_SINISTRE,"
            . " mt_CUMULE_PAYE = $mt_CUMULE_PAYE,"
            . " mt_PROVISION = $mt_PROVISION,"
            . " str_CONTRAT_ID = $str_CONTRAT_ID,"
            . " mt_BONI = $int_BONI,"
            . " mt_MALI = $int_MALI,"
            . " mt_BONI_POTENTIEL = $mt_BONI_POTENTIEL,"
            . " str_OBSERVATION = $str_OBSERVATION,"
            . " str_AUTRE_OBSERVATION_ID = $str_AUTRE_OBSERVATION_ID,"
            . " str_ETAT_ID = $str_ETAT_ID,"
            . " str_STATUT = $str_STATUT,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_SINISTRE_ID = $str_SINISTER_ID";
        //exit();

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllSinisterTreat( $str_SINISTER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_SINISTER_ID == "" || $str_SINISTER_ID == null) {
            $str_SINISTER_ID = "%%";
        }
        $str_SINISTER_ID = $db -> quote($str_SINISTER_ID);

        $sql = "SELECT t_sinistre.*, t_security.str_NOM, t_contrat.str_NUMERO_POLICE, t_etat.str_LIBELLE, t_autre_observation.str_LIBELLE AS str_AUTRE_OBSERVATION "
            .  " FROM t_sinistre "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_sinistre.str_CONTRAT_ID "
            . " JOIN t_autre_observation ON t_autre_observation.str_AUTRE_OBSERVATION_ID = t_sinistre.str_AUTRE_OBSERVATION_ID "
            . " JOIN t_etat ON t_etat.str_ETAT_ID = t_sinistre.str_ETAT_ID "
            . " JOIN t_security ON t_security.str_SECURITY_ID = t_sinistre.str_UPDATED_BY "
            . " WHERE t_sinistre.str_SINISTRE_ID LIKE " . $str_SINISTER_ID . " AND t_sinistre.str_STATUT <> '".$str_STATUT."' AND t_sinistre.str_STATUT LIKE 'edite' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getStatisticalSinistreTreat($str_SINISTRE_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "edite";
        $intResult = 0;
        $lg_CUSTOMER_ID = "";
        if($str_SINISTRE_ID == "" || $str_SINISTRE_ID == null){
            $str_SINISTRE_ID = "%%";
        }
        $annee = date('Y');
        $str_SECURITY_ID = $db -> quote($_SESSION['str_SECURITY_ID']);
        $str_SINISTRE_ID = $db -> quote($str_SINISTRE_ID);
        $str_STATUT = $db->quote($str_STATUT);
        /*$sql = "SELECT COUNT(str_SINISTRE_ID) AS nbr_sinistre, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_sinistre "
            ."WHERE str_CREATED_BY LIKE $str_SECURITY_ID "
            . "AND str_STATUT LIKE $str_STATUT "
            . "AND str_UPDATED_BY LIKE $str_SINISTRE_ID "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');"; **/
        $sql = "SELECT COUNT(str_SINISTRE_ID) AS nbr_sinistre, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_sinistre "
            . "WHERE str_STATUT LIKE $str_STATUT "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');";

        $stmt = $db -> query($sql);
        $arraySql = "";
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fichier_stat = fopen('graph_sinistre_traite_per_month.txt','w+');
        foreach ($result as $item_result2) {
            fputs($fichier_stat, $item_result2["nbr_sinistre"].','.$item_result2["mois"]);
            fputs($fichier_stat,"\r\n");
            //$arraySql[] = $item_result2;
            $intResult++;
            $code_statut = "1";
        }
        fclose($fichier_stat);
        /*$sql = "SELECT COUNT(str_SINISTRE_ID) AS nbr_sinistre, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_sinistre "
            ."WHERE str_CREATED_BY LIKE $str_SECURITY_ID "
            . "AND str_STATUT LIKE 'enable' "
            . "AND str_UPDATED_BY LIKE $str_SINISTRE_ID "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');";*/
        $sql = "SELECT COUNT(str_SINISTRE_ID) AS nbr_sinistre, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_sinistre "
            . "WHERE str_STATUT LIKE 'enable' "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');";
        $stmt = $db -> query($sql);
        $arraySql = "";
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fichier_stat = fopen('graph_sinistre_open_per_month.txt','w+');
        foreach ($result as $item_result2) {
            fputs($fichier_stat, $item_result2["nbr_sinistre"].','.$item_result2["mois"]);
            fputs($fichier_stat,"\r\n");
            //$arraySql[] = $item_result2;
            $intResult++;
            $code_statut = "1";
        }
        fclose($fichier_stat);
    }
    function getAmoutBoniMaliBoniPotentiel( $str_SINISTER_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_SINISTER_ID == "" || $str_SINISTER_ID == null) {
            $str_SINISTER_ID = "%%";
        }
        $str_SINISTER_ID = $db -> quote($str_SINISTER_ID);

        $sql = "SELECT SUM(mt_BONI) as mt_BONI, SUM(mt_MALI) AS mt_MALI, SUM(mt_BONI_POTENTIEL) as 	mt_BONI_POTENTIEL "
            .  " FROM t_sinistre "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_sinistre.str_CONTRAT_ID "
            . " JOIN t_autre_observation ON t_autre_observation.str_AUTRE_OBSERVATION_ID = t_sinistre.str_AUTRE_OBSERVATION_ID "
            . " JOIN t_etat ON t_etat.str_ETAT_ID = t_sinistre.str_ETAT_ID "
            . " JOIN t_security ON t_security.str_SECURITY_ID = t_sinistre.str_UPDATED_BY "
            . " WHERE t_sinistre.str_SINISTRE_ID LIKE " . $str_SINISTER_ID . " AND t_sinistre.str_STATUT <> '".$str_STATUT."' AND t_sinistre.str_STATUT LIKE 'edite' ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getAllProduction($str_PRODUCTION_ID, $db){
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_PRODUCTION_ID == "" || $str_PRODUCTION_ID == null) {
            $str_PRODUCTION_ID = "%%";
        }
        $str_PRODUCTION_ID = $db -> quote($str_PRODUCTION_ID);
        //ici la requete sert à récupérer les productions dont les avenants sont maximum
        $sql = "SELECT p.*, str_NOM, str_NUMERO_POLICE, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " WHERE str_PRODUCTION_ID LIKE " . $str_PRODUCTION_ID . " AND p.str_STATUT <> '".$str_STATUT."' AND int_AVENAN = (SELECT MAX(int_AVENAN) FROM t_production WHERE str_CONTRAT_ID = p.str_CONTRAT_ID); ";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistProduction($str_PRODUCTION_ID, $db) {
        $str_STATUT = 'delete';
        $str_PRODUCTION_ID = $db->quote($str_PRODUCTION_ID);
        $sql = "SELECT * FROM t_production WHERE str_PRODUCTION_ID LIKE " . $str_PRODUCTION_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_PRODUCTION_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function getMaxAvenant($str_CONTRAT_ID, $db) {
        $str_STATUT = 'delete';
        $str_CONTRAT_ID = $db->quote($str_CONTRAT_ID);
        $sql = "SELECT MAX(int_AVENAN) AS int_AVENAN FROM t_production WHERE str_CONTRAT_ID LIKE " . $str_CONTRAT_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_CONTRAT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($result)>0)
                {
                    foreach ($result as $item_result) {
                        return $item_result['int_AVENAN'] + 1;
                    }
                }
                return 0;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return 0;
            }
        }
        return 0;
    }
    function addProduction($db,$str_CONTRACT_ID, $str_INTERMEDIATE_ID, $dt_EFFET, $dt_ECHEANCE, $int_PRIME_TTC, $int_COMMISSION, $int_PRIME_NET, $int_TAUX_COMMISSION, $int_AVENANT, $int_MAJO){
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_PRODUCTION_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));

        $int_AVENANT = getMaxAvenant($str_CONTRACT_ID, $db);

        $sql = "INSERT INTO t_production(str_PRODUCTION_ID, dt_EFFET, dt_ECHEANCE, int_PRIME_TTC, int_COMMISSION, int_PRIME_NET, int_TAUX_COMMISSION, int_AVENAN, int_MAJO, str_STATUT, str_CREATED_BY, dt_CREATED, str_INTERMEDIAIRE_ID, str_CONTRAT_ID) "
            . "VALUES (:str_PRODUCTION_ID, :dt_EFFET, :dt_ECHEANCE, :int_PRIME_TTC, :int_COMMISSION, :int_PRIME_NET, :int_TAUX_COMMISSION, :int_AVENAN, :int_MAJO, :str_STATUT, :str_CREATED_BY, $dt_CREATED, :str_INTERMEDIAIRE_ID, :str_CONTRAT_ID)";
        try {
            if (!isExistProduction($str_PRODUCTION_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_PRODUCTION_ID', $str_PRODUCTION_ID);
                $stmt->BindParam(':dt_EFFET', $dt_EFFET);
                $stmt->BindParam(':dt_ECHEANCE', $dt_ECHEANCE);
                $stmt->BindParam(':int_PRIME_TTC', $int_PRIME_TTC);
                $stmt->BindParam(':int_COMMISSION', $int_COMMISSION);
                $stmt->BindParam(':int_PRIME_NET', $int_PRIME_NET);
                $stmt->BindParam(':int_TAUX_COMMISSION', $int_TAUX_COMMISSION);
                $stmt->BindParam(':int_AVENAN', $int_AVENANT);
                $stmt->BindParam(':int_MAJO', $int_MAJO);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                $stmt->BindParam(':str_INTERMEDIAIRE_ID', $str_INTERMEDIATE_ID);
                $stmt->BindParam(':str_CONTRAT_ID', $str_CONTRACT_ID);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_PRODUCTION_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteProduction($str_PRODUCTION_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_PRODUCTION_ID = $db->quote($str_PRODUCTION_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_production "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_PRODUCTION_ID = $str_PRODUCTION_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editProduction($str_PRODUCTION_ID, $str_CONTRACT_ID, $str_INTERMEDIATE_ID, $dt_EFFET, $dt_ECHEANCE, $int_PRIME_TTC, $int_COMMISSION, $int_PRIME_NET, $int_TAUX_COMMISSION, $int_AVENANT, $int_MAJO, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_PRODUCTION_ID = $db->quote($str_PRODUCTION_ID);
        $str_CONTRACT_ID = $db->quote($str_CONTRACT_ID);
        $str_INTERMEDIATE_ID = $db->quote($str_INTERMEDIATE_ID);
        $dt_EFFET = $db->quote($dt_EFFET);
        $dt_ECHEANCE = $db->quote($dt_ECHEANCE);
        $int_PRIME_TTC = $db->quote($int_PRIME_TTC);
        $int_COMMISSION = $db->quote($int_COMMISSION);
        $int_PRIME_NET = $db->quote($int_PRIME_NET);
        $int_TAUX_COMMISSION = $db->quote($int_TAUX_COMMISSION);
        //$int_AVENANT = $db->quote($int_AVENANT);
        $int_MAJO = $db->quote($int_MAJO);

        $sql = "UPDATE t_production "
            . "SET str_CONTRAT_ID = $str_CONTRACT_ID,"
            . " str_INTERMEDIAIRE_ID = $str_INTERMEDIATE_ID,"
            . " dt_EFFET = $dt_EFFET,"
            . " dt_ECHEANCE = $dt_ECHEANCE,"
            . " int_PRIME_TTC = $int_PRIME_TTC,"
            . " int_COMMISSION = $int_COMMISSION,"
            . " int_PRIME_NET = $int_PRIME_NET,"
            . " int_TAUX_COMMISSION = $int_TAUX_COMMISSION,"
            //. " int_AVENAN = $int_AVENANT,"
            . " int_MAJO = $int_MAJO,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_PRODUCTION_ID = $str_PRODUCTION_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllCollection($str_COLLECTION_ID, $db) {
        //echo "collection";
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_COLLECTION_ID == "" || $str_COLLECTION_ID == null) {
            $str_COLLECTION_ID = "%%";
        }
        $str_COLLECTION_ID = $db -> quote($str_COLLECTION_ID);

        $sql = "SELECT * FROM t_encaissement "
            . " WHERE str_ENCAISSEMENT_ID LIKE " . $str_COLLECTION_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeCollection($str_COLLECTION_ID, $db) {
        $str_STATUT = 'delete';
        $str_COLLECTION_ID = $db->quote($str_COLLECTION_ID);
        $sql = "SELECT * FROM t_encaissement WHERE str_ENCAISSEMENT_ID LIKE " . $str_COLLECTION_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_COLLECTION_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addCollection( $db, $str_LETTRAGE, $str_NUMERO_POLICE, $int_PRIME_TTC, $dt_EFFET, $dt_ECHEANCE) {
        $arrayJson = array();
        $message = "";
        //ECHO $str_NUMERO_POLICE;
        $code_statut = "";
        $str_STATUT = "enable";
        $str_ENCAISSEMENT_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_encaissement(str_ENCAISSEMENT_ID, str_NUMERO_LETTRAGE, pk_NUMERO_POLICE, int_PRIME_TTC, dt_EFFET, dt_ECHEANCE, str_STATUT, str_CREATED_BY, dt_CREATED) "
            . "VALUES (:str_ENCAISSEMENT_ID, :str_NUMERO_LETTRAGE, :pk_NUMERO_POLICE, :int_PRIME_TTC, :dt_EFFET, :dt_ECHEANCE, :str_STATUT, :str_CREATED_BY, $dt_CREATED)";
        try {
            if (!isExistCodeCollection($str_ENCAISSEMENT_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_ENCAISSEMENT_ID', $str_ENCAISSEMENT_ID);
                $stmt->BindParam(':str_NUMERO_LETTRAGE', $str_LETTRAGE);
                $stmt->BindParam(':pk_NUMERO_POLICE', $str_NUMERO_POLICE);
                $stmt->BindParam(':int_PRIME_TTC', $int_PRIME_TTC);
                $stmt->BindParam(':dt_EFFET', $dt_EFFET);
                $stmt->BindParam(':dt_ECHEANCE', $dt_ECHEANCE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_ENCAISSEMENT_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteCollection($str_COLLECTION_ID, $db){
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_COLLECTION_ID = $db->quote($str_COLLECTION_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_encaissement "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_ENCAISSEMENT_ID = $str_COLLECTION_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editCollection($str_COLLECTION_ID, $str_LETTRAGE, $str_NUMERO_POLICE, $int_PRIME_TTC, $dt_EFFET, $dt_ECHEANCE, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_LETTRAGE = $db->quote($str_LETTRAGE);
        $str_COLLECTION_ID = $db->quote($str_COLLECTION_ID);
        $str_NUMERO_POLICE = $db->quote($str_NUMERO_POLICE);
        $int_PRIME_TTC = $db->quote($int_PRIME_TTC);
        $dt_EFFET = $db->quote($dt_EFFET);
        $dt_ECHEANCE = $db->quote($dt_ECHEANCE);
        $sql = "UPDATE t_encaissement "
            . "SET str_NUMERO_LETTRAGE = $str_LETTRAGE,"
            . " pk_NUMERO_POLICE = $str_NUMERO_POLICE,"
            . " int_PRIME_TTC = $int_PRIME_TTC,"
            . " dt_EFFET = $dt_EFFET,"
            . " dt_ECHEANCE = $dt_ECHEANCE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_ENCAISSEMENT_ID = $str_COLLECTION_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }

    function getProductionStatistical($str_SINISTRE_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $intResult = 0;
        $lg_CUSTOMER_ID = "";
        if($str_SINISTRE_ID == "" || $str_SINISTRE_ID == null){
            $str_SINISTRE_ID = "%%";
        }
        $annee = date('Y');
        $str_SECURITY_ID = $db -> quote($_SESSION['str_SECURITY_ID']);
        $str_SINISTRE_ID = $db -> quote($str_SINISTRE_ID);
        $str_STATUT = $db->quote($str_STATUT);
        $sql = "SELECT COUNT(str_PRODUCTION_ID) AS nbr_production, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_production "
            ."WHERE str_CREATED_BY LIKE $str_SECURITY_ID "
            . "AND str_STATUT LIKE $str_STATUT "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');";

        $stmt = $db -> query($sql);
        $arraySql = "";
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fichier_stat = fopen('graph_sinistre_production_month.txt','w+');
        foreach ($result as $item_result2) {
            fputs($fichier_stat, $item_result2["nbr_production"].','.$item_result2["mois"]);
            fputs($fichier_stat,"\r\n");
            //$arraySql[] = $item_result2;
            $intResult++;
            $code_statut = "1";
        }
        fclose($fichier_stat);
        $sql = "SELECT COUNT(str_ENCAISSEMENT_ID) AS nbr_encaissemment, DATE_FORMAT(dt_CREATED,'%m') AS mois "
            ."FROM t_encaissement "
            ."WHERE str_CREATED_BY LIKE $str_SECURITY_ID "
            . "AND str_STATUT LIKE 'enable' "
            . "AND DATE_FORMAT(dt_CREATED,'%Y') LIKE $annee "
            . "GROUP BY DATE_FORMAT(dt_CREATED,'%m');";
        $stmt = $db -> query($sql);
        $arraySql = "";
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fichier_stat = fopen('graph_sinistre_encaissement_month.txt','w+');
        foreach ($result as $item_result2) {
            fputs($fichier_stat, $item_result2["nbr_encaissemment"].','.$item_result2["mois"]);
            fputs($fichier_stat,"\r\n");
            //$arraySql[] = $item_result2;
            $intResult++;
            $code_statut = "1";
        }
        fclose($fichier_stat);
    }

    function getAllProdNotCollected($str_PRODUCTION_ID, $db){
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_PRODUCTION_ID == "" || $str_PRODUCTION_ID == null) {
            $str_PRODUCTION_ID = "%%";
        }
        $str_PRODUCTION_ID = $db -> quote($str_PRODUCTION_ID);
        //ici la requete sert à récupérer les productions dont les avenants sont maximum
        $sql = "SELECT p.*, str_NOM, str_NUMERO_POLICE, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " WHERE str_PRODUCTION_ID LIKE " . $str_PRODUCTION_ID . " AND p.str_STATUT <> '".$str_STATUT."' AND int_AVENAN = (SELECT MAX(int_AVENAN) FROM t_production WHERE str_CONTRAT_ID = p.str_CONTRAT_ID) AND str_NUMERO_POLICE NOT IN (SELECT pk_NUMERO_POLICE FROM t_encaissement WHERE t_encaissement.dt_EFFET = p.dt_EFFET AND t_encaissement.dt_ECHEANCE = p.dt_ECHEANCE); ";

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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllProdCollected($str_PRODUCTION_ID, $db){
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        $intResult2 = 0;
        if ($str_PRODUCTION_ID == "" || $str_PRODUCTION_ID == null) {
            $str_PRODUCTION_ID = "%%";
        }
        $str_PRODUCTION_ID = $db -> quote($str_PRODUCTION_ID);
        //ici la requete sert à récupérer les productions dont les avenants sont maximum
        $sql = "SELECT p.*, t_intermediaire.str_NOM, str_NUMERO_POLICE, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " WHERE str_PRODUCTION_ID LIKE " . $str_PRODUCTION_ID . " AND p.str_STATUT <> '".$str_STATUT."' AND int_AVENAN = (SELECT MAX(int_AVENAN) FROM t_production WHERE str_CONTRAT_ID = p.str_CONTRAT_ID) AND str_NUMERO_POLICE IN (SELECT pk_NUMERO_POLICE FROM t_encaissement WHERE p.dt_EFFET = dt_EFFET AND p.dt_ECHEANCE = dt_ECHEANCE); ";

        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item_result) {
            $intResult++;
            $code_statut = "1";
            //RECHERCHER LES ENCAISSEMENTS
            $sql = "SELECT SUM(int_PRIME_TTC) AS sum_PRIME_TTC FROM t_encaissement "
                . " WHERE pk_NUMERO_POLICE LIKE '" . $item_result['str_NUMERO_POLICE']."' AND dt_EFFET LIKE '".$item_result['dt_EFFET']."' AND dt_ECHEANCE LIKE '".$item_result['dt_ECHEANCE']."'" ;

            $stmt = $db -> query($sql);
            $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result2 as $item_result2) {
                $rest = ($item_result['int_PRIME_TTC'] - $item_result2['sum_PRIME_TTC']);
                if($item_result2['sum_PRIME_TTC'] >= $item_result['int_PRIME_TTC'] )
                {
                    $item_result += array('str_DECISION'=> 'Soldé');
                    $item_result += array('int_PAYE'=> number_format($item_result2['sum_PRIME_TTC'], 0, '', '.') );
                    $item_result += array('int_RESTE'=> number_format($rest, 0, '', '.'));
                    $item_result += array('int_REAL_REST'=> ($item_result['int_PRIME_TTC'] - $item_result2['sum_PRIME_TTC']));
                    $arraySql[] = $item_result;
                }
                else
                {
                    $item_result += array('str_DECISION'=> 'Impayé');
                    $item_result += array('int_PAYE'=> number_format($item_result2['sum_PRIME_TTC'], 0, '', '.') );
                    $item_result += array('int_RESTE'=> number_format($rest, 0, '', '.'));
                    $item_result += array('int_REAL_REST'=> $rest);
                    $arraySql[] = $item_result;
                }
            }
            //FIN DE LA RECHERCHE DES ENCAISSEMENTS
        }
        $arrayJson["results"] = $arraySql;
        $arrayJson["total"] = $intResult;
        $arrayJson["desc_statut"] = $db->errorInfo();
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }

    function extractXSLData($fileName, $db)
    {
        $intResult = 0;
        $str_STATUT = "delete";
        $code_statut = 0;

        $sql = "SELECT p.*, t_intermediaire.str_NOM, str_NUMERO_POLICE, t_client.str_NOM AS str_NOM_CLIENT, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID, t_branche.str_LIBELLE AS str_LIBELLE_BRANCHE FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_produit ON t_contrat.str_PRODUIT_ID = t_produit.str_PRODUIT_ID "
            . " JOIN t_branche ON t_branche.str_BRANCHE_ID = t_produit.str_BRANCHE_ID "
            . " JOIN t_client ON t_contrat.str_CLIENT_ID = t_client.str_CLIENT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " WHERE  p.str_STATUT <> '".$str_STATUT."' AND int_AVENAN = (SELECT MAX(int_AVENAN) FROM t_production WHERE str_CONTRAT_ID = p.str_CONTRAT_ID) AND str_NUMERO_POLICE IN (SELECT pk_NUMERO_POLICE FROM t_encaissement WHERE p.dt_EFFET = dt_EFFET AND p.dt_ECHEANCE = dt_ECHEANCE); ";

        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item_result) {
            $intResult++;
            $code_statut = "1";
            //RECHERCHER LES ENCAISSEMENTS
            $sql = "SELECT SUM(int_PRIME_TTC) AS sum_PRIME_TTC FROM t_encaissement "
                . " WHERE pk_NUMERO_POLICE LIKE '" . $item_result['str_NUMERO_POLICE']."' AND dt_EFFET LIKE '".$item_result['dt_EFFET']."' AND dt_ECHEANCE LIKE '".$item_result['dt_ECHEANCE']."'" ;

            $stmt = $db -> query($sql);
            $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result2 as $item_result2) {
                $rest = ($item_result['int_PRIME_TTC'] - $item_result2['sum_PRIME_TTC']);
                if($item_result2['sum_PRIME_TTC'] >= $item_result['int_PRIME_TTC'] )
                {
                    $item_result += array('str_DECISION'=> 'Soldé');
                    $item_result += array('int_PAYE'=> number_format($item_result2['sum_PRIME_TTC'], 0, '', '.') );
                    $item_result += array('int_RESTE'=> number_format($rest, 0, '', '.'));
                    $item_result += array('int_REAL_REST'=> ($item_result['int_PRIME_TTC'] - $item_result2['sum_PRIME_TTC']));
                    $arraySql[] = $item_result;
                }
                else
                {
                    if($rest<0)
                    {
                        $item_result += array('str_DECISION'=> 'AVOIR');
                        $item_result += array('int_PAYE'=> number_format($item_result2['sum_PRIME_TTC'], 0, '', '.') );
                        $item_result += array('int_RESTE'=> number_format($rest, 0, '', '.'));
                        $item_result += array('int_REAL_REST'=> $rest);
                        $arraySql[] = $item_result;
                    }
                    else
                    {
                        $item_result += array('str_DECISION'=> 'Impayé');
                        $item_result += array('int_PAYE'=> number_format($item_result2['sum_PRIME_TTC'], 0, '', '.') );
                        $item_result += array('int_RESTE'=> number_format($rest, 0, '', '.'));
                        $item_result += array('int_REAL_REST'=> $rest);
                        $arraySql[] = $item_result;
                    }
                }
            }
            //FIN DE LA RECHERCHE DES ENCAISSEMENTS

            //construction des data
            $data[] = array(
                "Numéro de police" => trim($item_result['str_NUMERO_POLICE']),
                "Date effet" => trim($item_result['dt_EFFET']),
                "Date échéance" => trim($item_result['dt_ECHEANCE']),
                "Prime TTC" => trim($item_result['int_PRIME_TTC']),
                "Prime nette" => trim($item_result['int_PRIME_NET']),
                "Montant payé" => trim($item_result['int_PAYE']),
                "Reste à payer" => trim($item_result['int_RESTE']),
                "Taux de commission" => trim($item_result['int_TAUX_COMMISSION']),
                "Avenant" => trim($item_result['int_AVENAN']),
                "Intermédiaire" => trim($item_result['str_NOM']),
                "Nom du client" => trim($item_result['str_NOM_CLIENT']),
                "Branche" => trim(utf8_decode($item_result['str_LIBELLE_BRANCHE'])),
                "Décision" => trim(utf8_decode($item_result['str_DECISION']))
            );
            //fin de construction des datas
        }
        /* var_dump($data);
        exit();
        die(); */
        echo "[".json_encode(Lib::generatecsv($data, $fileName, 'com_collected_production/extractions'))."]";
    }
    function extractXSLDataNotCollectedProduction($fileName, $db)
    {
        $intResult = 0;
        $str_STATUT = "delete";
        $code_statut = 0;
        $sql = "SELECT p.*, t_intermediaire.str_NOM, str_NUMERO_POLICE, t_client.str_NOM AS str_NOM_CLIENT, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID, t_branche.str_LIBELLE AS str_LIBELLE_BRANCHE FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_produit ON t_contrat.str_PRODUIT_ID = t_produit.str_PRODUIT_ID "
            . " JOIN t_branche ON t_branche.str_BRANCHE_ID = t_produit.str_BRANCHE_ID "
            . " JOIN t_client ON t_contrat.str_CLIENT_ID = t_client.str_CLIENT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " WHERE p.str_STATUT <> '".$str_STATUT."' AND int_AVENAN = (SELECT MAX(int_AVENAN) FROM t_production WHERE str_CONTRAT_ID = p.str_CONTRAT_ID) AND str_NUMERO_POLICE NOT IN (SELECT pk_NUMERO_POLICE FROM t_encaissement WHERE t_encaissement.dt_EFFET = p.dt_EFFET AND t_encaissement.dt_ECHEANCE = p.dt_ECHEANCE); ";

        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item_result) {
            $arraySql[] = $item_result;
            $intResult++;
            $code_statut = "1";
            $data[] = array(
                "Numéro de police" => trim($item_result['str_NUMERO_POLICE']),
                "Date effet" => trim($item_result['dt_EFFET']),
                "Date échéance" => trim($item_result['dt_ECHEANCE']),
                "Prime TTC" => trim($item_result['int_PRIME_TTC']),
                "Prime nette" => trim($item_result['int_PRIME_NET']),
                "Taux" => trim(utf8_decode($item_result['int_TAUX_COMMISSION'])),
                "Majoration" => trim(utf8_decode($item_result['int_MAJO'])),
                "Nom du client" => trim($item_result['str_NOM_CLIENT']),
                "Branche" => trim(utf8_decode($item_result['str_LIBELLE_BRANCHE'])),
                "Intermédiaire" => trim(utf8_decode($item_result['str_NOM']))
            );
        }

        echo "[".json_encode(Lib::generatecsv($data, $fileName, 'com_not_collected_production/extractions'))."]";
    }
    function extractXSLDataCollectedNotProduction($fileName, $db)
    {
        $intResult = 0;
        $str_STATUT = "delete";
        $code_statut = 0;
        $sql = "SELECT * FROM t_encaissement e"
            . " WHERE str_STATUT <> '".$str_STATUT."'  AND pk_NUMERO_POLICE NOT IN "
            . "(SELECT str_NUMERO_POLICE FROM t_production "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_production.str_CONTRAT_ID "
            . " WHERE e.dt_EFFET = dt_EFFET AND e.dt_ECHEANCE = dt_ECHEANCE )";
        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $item_result) {
            $arraySql[] = $item_result;
            $intResult++;
            $code_statut = "1";
            $data[] = array(
                "Date de création" => trim($item_result['dt_CREATED']),
                "Numéro de lettrage" => trim($item_result['str_NUMERO_LETTRAGE']),
                "Numéro de police" => trim($item_result['pk_NUMERO_POLICE']),
                "Prime TTC" => trim($item_result['int_PRIME_TTC']),
                "Date effet" => trim($item_result['dt_EFFET']),
                "Echéance" => trim(utf8_decode($item_result['dt_ECHEANCE']))
            );
        }
        echo "[".json_encode(Lib::generatecsv($data, $fileName, 'com_not_collected_production/extractions'))."]";
    }
    function getAllCollectedPaie( $str_NUMERO_POLICE, $db) {
        $arrayJson = array();
        $arraySql = array();
        $arraySqlEncaissement = array();
        $arraySqlSunuPack = array();
        $code_statut = "0";
        $code_statutEncaissement = "0";
        $code_statutSunuPack = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        $intResultEncaissement = 0;
        $intResultSunuPack = 0;

        $getRest = 1;
        if ($str_NUMERO_POLICE == "" || $str_NUMERO_POLICE == null) {
            $str_NUMERO_POLICE = "%%";
            $getRest = 0;
        }
        $str_NUMERO_POLICE = $db -> quote($str_NUMERO_POLICE);
        //recuperattion des contrats
        $sql = "SELECT p.*, str_NUMERO_POLICE, p.str_INTERMEDIAIRE_ID, p.str_CONTRAT_ID, t_client.str_NOM FROM t_production p"
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = p.str_CONTRAT_ID "
            . " JOIN t_intermediaire ON t_intermediaire.str_INTERMEDIAIRE_ID = p.str_INTERMEDIAIRE_ID "
            . " JOIN t_client ON t_contrat.str_CLIENT_ID = t_client.str_CLIENT_ID "
            . " WHERE str_NUMERO_POLICE LIKE " . $str_NUMERO_POLICE . " AND p.str_STATUT <> '".$str_STATUT."'  AND str_NUMERO_POLICE IN (SELECT pk_NUMERO_POLICE FROM t_encaissement WHERE p.dt_EFFET = dt_EFFET AND p.dt_ECHEANCE = dt_ECHEANCE); ";

        $stmt = $db -> query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $int_MONTANT_ENCAISSEMENT = 0;
        foreach ($result as $item_result) {
            $arraySql[] = $item_result;
            $intResult++;
            $code_statut = "1";
            if($getRest>0){
                //verifions que la production à été payé
                $dt_EFFET = $db->quote($item_result['dt_EFFET']);
                $dt_ECHEANCE = $db->quote($item_result['dt_ECHEANCE']);

                $sqlEncaissement = "SELECT * FROM t_encaissement "
                    . " WHERE pk_NUMERO_POLICE LIKE " .$str_NUMERO_POLICE . " AND str_STATUT <> '".$str_STATUT."' AND dt_EFFET LIKE $dt_EFFET AND dt_ECHEANCE LIKE $dt_ECHEANCE ";
                $stmtEncaissement = $db -> query($sqlEncaissement);
                $resultEncaissement = $stmtEncaissement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultEncaissement as $item_resultEncaissement) {
                    $arraySqlEncaissement[] = $item_resultEncaissement;
                    $intResultEncaissement++;
                    $code_statutEncaissement = "1";
                    $int_MONTANT_ENCAISSEMENT += $item_resultEncaissement['int_PRIME_TTC'];
                }
            }
        }

        $arrayJson["resultsEncaissement"] = $arraySqlEncaissement;
        $arrayJson["totalEncaissement"] = $intResultEncaissement;
        $arrayJson["code_statutEncaissement"] = $code_statutEncaissement;

        $arrayJson["results"] = $arraySql;
        $arrayJson["total"] = $intResult;
        $arrayJson["desc_statut"] = $db->errorInfo();
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllCollectedNotProd($str_COLLECTION_ID, $db){
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        $intResult2 = 0;
        if ($str_COLLECTION_ID == "" || $str_COLLECTION_ID == null) {
            $str_COLLECTION_ID = "%%";
        }
        $str_COLLECTION_ID = $db -> quote($str_COLLECTION_ID);
        //ici la requete sert à récupérer les productions dont les avenants sont maximum

        $sql = "SELECT * FROM t_encaissement e"
            . " WHERE str_ENCAISSEMENT_ID LIKE " . $str_COLLECTION_ID . " AND str_STATUT <> '".$str_STATUT."'  AND pk_NUMERO_POLICE NOT IN "
            . "(SELECT str_NUMERO_POLICE FROM t_production "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_production.str_CONTRAT_ID "
            . " WHERE e.dt_EFFET = dt_EFFET AND e.dt_ECHEANCE = dt_ECHEANCE )";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllReceipt($str_RECEIPT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_RECEIPT_ID == "" || $str_RECEIPT_ID == null) {
            $str_RECEIPT_ID = "%%";
        }
        $str_RECEIPT_ID = $db -> quote($str_RECEIPT_ID);

        $sql = "SELECT t_quittance.*, t_quittance.str_ENCAISSEMENT_ID, str_NUMERO_LETTRAGE FROM t_quittance "
            . " JOIN t_encaissement ON t_encaissement.str_ENCAISSEMENT_ID = t_quittance.str_ENCAISSEMENT_ID "
            . " WHERE str_QUITTANCE_ID LIKE " . $str_RECEIPT_ID . " AND t_quittance.str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeReceipt($str_RECEIPT_ID, $db) {
        $str_STATUT = 'delete';
        $str_RECEIPT_ID = $db->quote($str_RECEIPT_ID);
        $sql = "SELECT * FROM t_quittance WHERE str_QUITTANCE_ID LIKE " . $str_RECEIPT_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_RECEIPT_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addReceipt( $db, $str_QUITTANCE, $str_LETTRAGE) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_RECEIPT_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        $sql = "INSERT INTO t_quittance(str_QUITTANCE_ID, str_LIBELLE, str_ENCAISSEMENT_ID, str_STATUT, str_CREATED_BY, dt_CREATED) "
            . "VALUES (:str_QUITTANCE_ID, :str_LIBELLE, :str_ENCAISSEMENT_ID, :str_STATUT,:str_CREATED_BY,$dt_CREATED)";
        try {
            if (!isExistCodeReceipt($str_RECEIPT_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_QUITTANCE_ID', $str_RECEIPT_ID);
                $stmt->BindParam(':str_LIBELLE', $str_QUITTANCE);
                $stmt->BindParam(':str_ENCAISSEMENT_ID', $str_LETTRAGE);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_RECEIPT_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editReceipt($str_QUITTANCE, $str_LETTRAGE, $str_QUITTANCE_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_QUITTANCE = $db->quote($str_QUITTANCE);
        $str_LETTRAGE = $db->quote($str_LETTRAGE);
        $str_QUITTANCE_ID = $db->quote($str_QUITTANCE_ID);
        $sql = "UPDATE t_quittance "
            . "SET str_LIBELLE = $str_QUITTANCE,"
            . " str_ENCAISSEMENT_ID = $str_LETTRAGE,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_QUITTANCE_ID = $str_QUITTANCE_ID";

        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteReceipt($str_RECEIPT_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_RECEIPT_ID = $db->quote($str_RECEIPT_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_quittance "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_QUITTANCE_ID = $str_RECEIPT_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllCompanyContract($str_COMPANY_CONTRACT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_COMPANY_CONTRACT_ID == "" || $str_COMPANY_CONTRACT_ID == null) {
            $str_COMPANY_CONTRACT_ID = "%%";
        }
         $str_COMPANY_CONTRACT_ID = $db -> quote($str_COMPANY_CONTRACT_ID);

        $sql = "SELECT t_contrat_entreprise.*, t_contrat.str_NUMERO_POLICE, t_contrat_entreprise.str_CONTRAT_ID FROM t_contrat_entreprise "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_contrat_entreprise.str_CONTRAT_ID "
            . " WHERE str_CONTRATENTREPRISE_ID LIKE " . $str_COMPANY_CONTRACT_ID . " AND t_contrat_entreprise.str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function isExistCodeCompanyContract($str_CONTRATENTREPRISE_ID, $db) {
        $str_STATUT = 'delete';
        $str_CONTRATENTREPRISE_ID = $db->quote($str_CONTRATENTREPRISE_ID);
        $sql = "SELECT * FROM t_contrat_entreprise WHERE str_CONTRATENTREPRISE_ID LIKE " . $str_CONTRATENTREPRISE_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_CONTRATENTREPRISE_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addCompanyContract( $db, $str_CONTRAT_ID, $dt_ENTREE, $dt_SORTIE, $str_IMMATRICULATION, $str_CODE_REGULATION, $int_NBRPLACE, $int_VALEUR, $int_POID) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_CONTRATENTREPRISE_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));
        if($dt_SORTIE<>"")
        {
            $sql = "INSERT INTO t_contrat_entreprise(str_CONTRATENTREPRISE_ID, dt_ENTRE, dt_SORTIE, str_IMMATRICULATION, str_CODE_REGULATION, int_NBRPLACE, int_VALEUR_A_NEUF, int_POID, str_STATUT, str_CREATED_BY, dt_CREATED, str_CONTRAT_ID) "
                . "VALUES (:str_CONTRATENTREPRISE_ID, :dt_ENTRE, :dt_SORTIE, :str_IMMATRICULATION, :str_CODE_REGULATION, :int_NBRPLACE, :int_VALEUR_A_NEUF, :int_POID, :str_STATUT, :str_CREATED_BY, $dt_CREATED, :str_CONTRAT_ID)";
        }
        else
        {
            $sql = "INSERT INTO t_contrat_entreprise(str_CONTRATENTREPRISE_ID, dt_ENTRE, str_IMMATRICULATION, str_CODE_REGULATION, int_NBRPLACE, int_VALEUR_A_NEUF, int_POID, str_STATUT, str_CREATED_BY, dt_CREATED, str_CONTRAT_ID) "
                . "VALUES (:str_CONTRATENTREPRISE_ID, :dt_ENTRE, :str_IMMATRICULATION, :str_CODE_REGULATION, :int_NBRPLACE, :int_VALEUR_A_NEUF, :int_POID, :str_STATUT, :str_CREATED_BY, $dt_CREATED, :str_CONTRAT_ID)";
        }
        try {
            if (!isExistCodeCompanyContract($str_CONTRATENTREPRISE_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_CONTRATENTREPRISE_ID', $str_CONTRATENTREPRISE_ID);
                $stmt->BindParam(':dt_ENTRE', $dt_ENTREE);
                if($dt_SORTIE<>"")$stmt->BindParam(':dt_SORTIE', $dt_SORTIE);
                $stmt->BindParam(':str_IMMATRICULATION', $str_IMMATRICULATION);
                $stmt->BindParam(':str_CODE_REGULATION', $str_CODE_REGULATION);
                $stmt->BindParam(':int_NBRPLACE', $int_NBRPLACE);
                $stmt->BindParam(':int_VALEUR_A_NEUF', $int_VALEUR);
                $stmt->BindParam(':int_POID', $int_POID);
                $stmt->BindParam(':str_CONTRAT_ID', $str_CONTRAT_ID);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_CONTRATENTREPRISE_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function editCompanyContract($str_NUMERO_POLICE, $dt_ENTREE, $dt_SORTIE, $str_IMMATRICULATION, $str_CODE_REGULATION, $int_NBRPLACE, $int_VALEUR, $int_POID, $str_CONTRAT_ENTREPRISE_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_CONTRAT_ID = $db->quote($str_NUMERO_POLICE);
        $dt_ENTREE = $db->quote($dt_ENTREE);
        $dt_SORTIE = $db->quote($dt_SORTIE);
        $str_IMMATRICULATION = $db->quote($str_IMMATRICULATION);
        $str_CODE_REGULATION = $db->quote($str_CODE_REGULATION);
        $int_NBRPLACE = $db->quote($int_NBRPLACE);
        $int_VALEUR = $db->quote($int_VALEUR);
        $int_POID = $db->quote($int_POID);
        $str_CONTRAT_ENTREPRISE_ID = $db->quote($str_CONTRAT_ENTREPRISE_ID);
        $sql = "UPDATE t_contrat_entreprise "
            . "SET str_CONTRAT_ID = $str_CONTRAT_ID,"
            . " dt_ENTRE = $dt_ENTREE,"
            . " str_IMMATRICULATION = $str_IMMATRICULATION,"
            . " str_CODE_REGULATION = $str_CODE_REGULATION,"
            . " int_NBRPLACE = $int_NBRPLACE,"
            . " int_VALEUR_A_NEUF = $int_VALEUR,"
            . " int_POID = $int_POID,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_CONTRATENTREPRISE_ID = $str_CONTRAT_ENTREPRISE_ID";

        //var_dump($dt_SORTIE);
        if($dt_SORTIE<>"''")
        {
            $sql = "UPDATE t_contrat_entreprise "
                . "SET str_CONTRAT_ID = $str_CONTRAT_ID,"
                . " dt_ENTRE = $dt_ENTREE,"
                . " dt_SORTIE = $dt_SORTIE,"
                . " str_IMMATRICULATION = $str_IMMATRICULATION,"
                . " str_CODE_REGULATION = $str_CODE_REGULATION,"
                . " int_NBRPLACE = $int_NBRPLACE,"
                . " int_VALEUR_A_NEUF = $int_VALEUR,"
                . " int_POID = $int_POID,"
                . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
                . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
                . " WHERE str_CONTRATENTREPRISE_ID = $str_CONTRAT_ENTREPRISE_ID";

        }
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }
    function deleteCompanyContract($str_COMPANY_CONTRACT_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_COMPANY_CONTRACT_ID = $db->quote($str_COMPANY_CONTRACT_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_contrat_entreprise "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_CONTRATENTREPRISE_ID = $str_COMPANY_CONTRACT_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllReassur($str_REASSUR_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_REASSUR_ID == "" || $str_REASSUR_ID == null) {
            $str_REASSUR_ID = "%%";
        }
        $str_REASSUR_ID = $db -> quote($str_REASSUR_ID);

        $sql = "SELECT t_reassur.*, t_production.dt_EFFET, t_production.dt_ECHEANCE, t_reassureur.str_REASSUREUR_ID, t_reassureur.str_NOM, t_contrat.str_NUMERO_POLICE FROM t_reassur "
            . " JOIN t_production ON t_production.str_PRODUCTION_ID = t_reassur.str_PRODUCTION_ID "
            . " JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_production.str_CONTRAT_ID "
            . " JOIN t_reassureur ON t_reassureur.str_REASSUREUR_ID = t_reassur.str_REASSUREUR_ID "
            . " WHERE str_REASSUR_ID LIKE " . $str_REASSUR_ID . " AND t_reassur.str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY dt_CREATED DESC;";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAvenant($str_CONTRAT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_CONTRAT_ID == "" || $str_CONTRAT_ID == null) {
            $str_CONTRAT_ID = "%%";
        }
        $str_CONTRAT_ID = $db -> quote($str_CONTRAT_ID);

        $sql = "SELECT str_PRODUCTION_ID, int_AVENAN FROM t_production "
            . " WHERE str_CONTRAT_ID LIKE " . $str_CONTRAT_ID . " AND str_STATUT <> '".$str_STATUT."' "
            . " ORDER BY int_AVENAN ASC ";
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
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getAllContractWhoHaveProduction( $str_CONTRACT_ID, $db) {
        $arrayJson = array();
        $arraySql = array();
        $code_statut = "0";
        $str_STATUT = "delete";
        $intResult = 0;
        if ($str_CONTRACT_ID == "" || $str_CONTRACT_ID == null) {
            $str_CONTRACT_ID = "%%";
        }
        $str_CONTRACT_ID = $db -> quote($str_CONTRACT_ID);
        $sql = "SELECT * FROM t_contrat 
                    WHERE str_CONTRAT_ID LIKE ".$str_CONTRACT_ID." AND t_contrat.str_STATUT <> 'delete' AND str_CONTRAT_ID IN (SELECT str_CONTRAT_ID FROM t_production ) ;";

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
        echo "[" . json_encode($arrayJson) . "]";
    }

    function isExistCodeReassur($str_REASSUR_ID, $db) {
        $str_STATUT = 'delete';
        $str_REASSUR_ID = $db->quote($str_REASSUR_ID);
        $sql = "SELECT * FROM t_reassur WHERE str_REASSUR_ID LIKE " . $str_REASSUR_ID . " AND str_STATUT <> '" . $str_STATUT . "'";
        if(!empty($str_REASSUR_ID)){
            try {
                $stmt = $db->query($sql);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (count($result) > 0) {
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                die("Erreur ! : " . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    function addReassur( $db, $int_AVENANT, $str_REASSUREUR_ID, $int_PAR_SUNU, $int_PAR_REASSUREUR) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "enable";
        $str_REASSUR_ID = RandomString();
        $dt_CREATED = $db->quote(gmdate("Y-m-d, H:i:s"));

        $sql = "INSERT INTO t_reassur(str_REASSUR_ID, par_SUNU, par_REASSUREUR, str_STATUT, str_CREATED_BY, dt_CREATED, str_REASSUREUR_ID, str_PRODUCTION_ID) "
            . "VALUES (:str_REASSUR_ID, :par_SUNU, :par_REASSUREUR, :str_STATUT, :str_CREATED_BY, $dt_CREATED, :str_REASSUREUR_ID, :str_PRODUCTION_ID)";

        try {
            if (!isExistCodeReassur($str_REASSUR_ID, $db)) {

                $stmt = $db->prepare($sql);
                $str_STATUT = "enable";
                $stmt->BindParam(':str_REASSUR_ID', $str_REASSUR_ID);
                $stmt->BindParam(':par_SUNU', $int_PAR_SUNU);
                $stmt->BindParam(':par_REASSUREUR', $int_PAR_REASSUREUR);
                $stmt->BindParam(':str_STATUT', $str_STATUT);
                $stmt->BindParam(':str_CREATED_BY', $_SESSION['str_SECURITY_ID']);
                $stmt->BindParam(':str_REASSUREUR_ID', $str_REASSUREUR_ID);
                $stmt->BindParam(':str_PRODUCTION_ID', $int_AVENANT);
                //var_dump($stmt);
                if ($stmt->execute()) {
                    $message = "Insertion effectué avec succès";
                    $code_statut = "1";
                } else {
                    $message = "Erreur lors de l'insertion";
                    $code_statut = "0";
                }
            } else {
                $message = "Ce Code  : \" " . $str_REASSUR_ID . " \" de table existe déja! \r\n";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["code_statut"] = $code_statut;
        $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }/*
    function editCompanyContract($str_NUMERO_POLICE, $dt_ENTREE, $dt_SORTIE, $str_IMMATRICULATION, $str_CODE_REGULATION, $int_NBRPLACE, $int_VALEUR, $int_POID, $str_CONTRAT_ENTREPRISE_ID, $db)
    {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        //ECHO $str_ETAT_ID;
        $str_CONTRAT_ID = $db->quote($str_NUMERO_POLICE);
        $dt_ENTREE = $db->quote($dt_ENTREE);
        $dt_SORTIE = $db->quote($dt_SORTIE);
        $str_IMMATRICULATION = $db->quote($str_IMMATRICULATION);
        $str_CODE_REGULATION = $db->quote($str_CODE_REGULATION);
        $int_NBRPLACE = $db->quote($int_NBRPLACE);
        $int_VALEUR = $db->quote($int_VALEUR);
        $int_POID = $db->quote($int_POID);
        $str_CONTRAT_ENTREPRISE_ID = $db->quote($str_CONTRAT_ENTREPRISE_ID);
        $sql = "UPDATE t_contrat_entreprise "
            . "SET str_CONTRAT_ID = $str_CONTRAT_ID,"
            . " dt_ENTRE = $dt_ENTREE,"
            . " str_IMMATRICULATION = $str_IMMATRICULATION,"
            . " str_CODE_REGULATION = $str_CODE_REGULATION,"
            . " int_NBRPLACE = $int_NBRPLACE,"
            . " int_VALEUR_A_NEUF = $int_VALEUR,"
            . " int_POID = $int_POID,"
            . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
            . " WHERE str_CONTRATENTREPRISE_ID = $str_CONTRAT_ENTREPRISE_ID";

        //var_dump($dt_SORTIE);
        if($dt_SORTIE<>"''")
        {
            $sql = "UPDATE t_contrat_entreprise "
                . "SET str_CONTRAT_ID = $str_CONTRAT_ID,"
                . " dt_ENTRE = $dt_ENTREE,"
                . " dt_SORTIE = $dt_SORTIE,"
                . " str_IMMATRICULATION = $str_IMMATRICULATION,"
                . " str_CODE_REGULATION = $str_CODE_REGULATION,"
                . " int_NBRPLACE = $int_NBRPLACE,"
                . " int_VALEUR_A_NEUF = $int_VALEUR,"
                . " int_POID = $int_POID,"
                . "str_UPDATED_BY = '" . $_SESSION['str_SECURITY_ID'] . "',"
                . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "'"
                . " WHERE str_CONTRATENTREPRISE_ID = $str_CONTRAT_ENTREPRISE_ID";

        }
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Modification effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        //    $arrayJson["desc_statut"] = $db->errorInfo();
        echo "[" . json_encode($arrayJson) . "]";
    }*/
    function deleteReassur($str_REASSUR_ID, $db) {
        $arrayJson = array();
        $message = "";
        $code_statut = "";
        $str_STATUT = "delete";
        $str_REASSUR_ID = $db->quote($str_REASSUR_ID);
        $str_UPDATED_BY = $db->quote($_SESSION['str_SECURITY_ID']);
        $sql = "UPDATE t_reassur "
            . "SET str_STATUT = '$str_STATUT',"
            . "str_UPDATED_BY = $str_UPDATED_BY, "
            . "dt_UPDATED = '" . gmdate("Y-m-d, H:i:s") . "' "
            . "WHERE str_REASSUR_ID = $str_REASSUR_ID";
        try {
            $sucess = $db->exec($sql);
            if ($sucess > 0) {
                $message = "Suppression effectuée avec succès";
                $code_statut = "1";
            } else {
                $message = "Erreur lors de la modification";
                $code_statut = "0";
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
        }
        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }

    function generateNoticeOfExpiry($db, $str_SINISTRE_ID)
    {
        $arrayJson = array();
        $message = "";
        $intResult = 0;
        $code_statut = "";
        $str_STATUT = "delete";
        $sucess = 0;
        if ($str_SINISTRE_ID == "" || $str_SINISTRE_ID == null) {
            $str_SINISTRE_ID = "%%";
        }

        //determination de la cahrge total
        $str_SINISTRE_ID = $db->quote($str_SINISTRE_ID);
        $sql = "SELECT str_SINISTRE_ID, mt_CUMULE_PAYE, mt_PROVISION FROM t_sinistre WHERE str_SINISTRE_ID LIKE " . $str_SINISTRE_ID . " AND str_STATUT <> '" . $str_STATUT . "' ";

        try {
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $item_result) {
                $str_SINISTRE_ID = $db->quote($item_result['str_SINISTRE_ID']);
                //determination de la charge TOTAL
                $charge_total = ($item_result['mt_CUMULE_PAYE'] + $item_result['mt_PROVISION']);

                //determination des autres charges
                $sqlAutreCharge = "SELECT int_COMMISSION, int_PRIME_NET, mt_CUMULE_PAYE FROM t_production "
                    .   "JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_production.str_CONTRAT_ID "
                    .   "JOIN t_sinistre ON t_sinistre.str_CONTRAT_ID = t_contrat.str_CONTRAT_ID "
                    .   "WHERE t_sinistre.str_SINISTRE_ID LIKE " . $str_SINISTRE_ID . " AND t_sinistre.str_STATUT <> '" . $str_STATUT . "' LIMIT 1";

                try {
                    $stmtAutreCharge = $db->query($sqlAutreCharge);
                    $resultAutreCharge = $stmtAutreCharge->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultAutreCharge as $item_resultAutreCharge) {
                        //determination des autres charges
                        $autre_charge = ($item_resultAutreCharge['int_COMMISSION'] + $item_resultAutreCharge['int_PRIME_NET'] * 0.16);

                        $part_cumule_paye_dans_charge = ($item_resultAutreCharge['mt_CUMULE_PAYE']/$charge_total);

                        //determination de la class de robustess
                        if($part_cumule_paye_dans_charge >= 0.7 )
                        {
                            $class_robustess = "class1";
                        }
                        else
                        {
                            $class_robustess = "class2";
                        }

                        //determination de sinistre sur prime (sinistralité)

                        if($class_robustess == "class1")
                        {
                            $sinistreSurPrime = $charge_total / $item_resultAutreCharge['int_PRIME_NET'];

                            $ratioCombine = ($charge_total + $autre_charge) / $item_resultAutreCharge['int_PRIME_NET'];
                        }
                        else{
                            $sinistreSurPrime = ($item_resultAutreCharge['mt_CUMULE_PAYE']+ (0.8*$item_result['mt_PROVISION'])) / $item_resultAutreCharge['int_PRIME_NET'];

                            $ratioCombine = ($item_resultAutreCharge['mt_CUMULE_PAYE']+(0.8*$item_result['mt_PROVISION'])+$autre_charge)/$item_resultAutreCharge['int_PRIME_NET'];
                        }
                    }
                } catch (PDOException $e) {
                    die("Erreur ! : " . $e->getMessage());
                    return false;
                }

                $sqlAutreCharge = "SELECT int_COMMISSION, int_PRIME_NET, mt_CUMULE_PAYE FROM t_production "
                    .   "JOIN t_contrat ON t_contrat.str_CONTRAT_ID = t_production.str_CONTRAT_ID "
                    .   "JOIN t_sinistre ON t_sinistre.str_CONTRAT_ID = t_contrat.str_CONTRAT_ID "
                    .   "WHERE t_sinistre.str_SINISTRE_ID LIKE " . $str_SINISTRE_ID . " AND t_sinistre.str_STATUT <> '" . $str_STATUT . "' LIMIT 1";

                try {
                    $stmtAutreCharge = $db->query($sqlAutreCharge);
                    $resultAutreCharge = $stmtAutreCharge->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultAutreCharge as $item_resultAutreCharge) {

                    }
                } catch (PDOException $e) {
                    die("Erreur ! : " . $e->getMessage());
                    return false;
                }
            }
        } catch (PDOException $e) {
            die("Erreur ! : " . $e->getMessage());
            return false;
        }

        $arrayJson["results"] = $message;
        $arrayJson["total"] = $sucess;
        $arrayJson["code_statut"] = $code_statut;
        echo "[" . json_encode($arrayJson) . "]";
    }
    function getDatabaseTables($db)
    {
        Lib::getDatabaseTable($db);
    }
    function describeTable($db, $table)
    {
        Lib::tablesDescription($db, $table);
    }
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }
    $return=NULL;
    //cycle through
    foreach($tables as $table)
    {
        $result = mysql_query('SELECT * FROM '.$table);
        $num_fields = mysql_num_fields($result);

        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";

        for ($i = 0; $i < $num_fields; $i++)
        {
            while($row = mysql_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j<$num_fields; $j++)
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("#\n#","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $path = 'backup/autosave/db-auto-backup-'.date("d-m-Y").'-'.(md5(implode(',',$tables))).'.sql';

    $filename = $path;


    if (!file_exists($filename)) {
        $handle = fopen($path,'w+');
        fwrite($handle,$return);
        fclose($handle);
    }

}
?>