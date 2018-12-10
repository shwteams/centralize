<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" class="no-js">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Sécurité | Support E-capafrica</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta content="Application de gestion des insidents" name="description"/>
        <meta content="Moroko jean-romaric" name="author"/>
        <!-- ajout des icon -->
        <link href="./services/css/font_/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <!-- fin ajout des icon -->
        <link href="./services/css/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="./services/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="./services/css/site.css" rel="stylesheet" type="text/css"/>
        <link href="./services/css/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <!-- Verif form -->
        <script src="./services/js/jquery.min.js"></script>
        <script src="./services/js/jquery.form.js"></script>
        <!-- END THEmE STYLES -->
        <script src="services/js/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="services/css/sweetalert.css">
        <script src="composant/com_security/security.js"></script>
        <link rel="shortcut icon" href="../../services/logo/ecap.png" alt="logo société"/>
    </head>
<!--[if IE 8]> <html lang="fr" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="fr" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><! onselectstart="return false" oncontextmenu="return false" ondragstart="return false" -->
    <body >
<!--<![endif]-->
        <div class="container">
          <div class="row" id="login-container">
            <div class="span8 offset4">
               <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 col-md-offset-3 marginTop10">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <i class="fa fa-lock"></i>
                                Connexion 
                            </div>
                        </div>
                        <div class="alert alert-warning no-radius no-margin padding-sm">
                            <i class="fa fa-info-circle"></i> 
                            Veuillez modifier votre mot de passe
                        </div>
                        <div class="panel-body" >
                            <form method="post" id="modal_edit_key" role="form" >
                                <div class="form-group">
                                    <label for="str_LOGIN">
                                        Nom d'utilisateur
                                    </label> 
                                    <input class="form-control" required id="str_LOGIN" name="str_LOGIN" placeholder="Login.." type="text" min="4" disabled/> 
                                </div>
                                <div class="form-group">
                                    <label for="str_PASSWORD">
                                        Mot de passe
                                    </label> 
                                    <input  required="required" class="form-control" id="str_PASSWORD" placeholder="Entrez un mot de passe" name="str_PASSWORD" type="password" min="5"/> 
                                </div>
                                <div class="form-group">
                                    <label for="str_PASSWORD2">
                                        Confirmer le mot de passe
                                    </label> 
                                    <input  required="required" class="form-control" id="str_PASSWORD2" placeholder="Entrez le même mot de passe" name="str_PASSWORD" type="password" min="5"/> 
                                </div>
                                <div class="form-group">
                                    <input id="lg_SECURITY_ID" name="lg_SECURITY_ID" type="hidden">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa fa-unlock"></i>
                                        Modifier
                                    </button>
                                </div>
                            </form>
                            <hr class="panel panel-info"/>
                            <div id="copy" class="text-center">
                                Copyright &copy 2017, E-capafrica Powered by 
                                <a href="mailto:morokojeanr@gmail.com" title="+225 75 19 15 45 ou clicquer pour envoyer un mail">Jean-Romaric Léby MOROKO</a>.
                            </div> 
                        </div>
                    </div>  
                    </div>
                </div>
            </div>
          </div>
    </div>
    </body>
</html>