<?php
if(!isset($_SESSION))
    session_start();
if(!isset($_SESSION['str_SECURITY_ID']))
{
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login | Centralize data</title>
        <script src="AdminLTE-master/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <!--script src="//code.jquery.com/jquery-1.12.0.js" type="text/javascript"></script-->
        <script src="AdminLTE-master/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="AdminLTE-master/dist/js/app.js" type="text/javascript"></script>
        <!--script src="AdminLTE-master/dist/js/pages/dashboard.js" type="text/javascript"></script-->
        <script src="AdminLTE-master/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="AdminLTE-master/plugins/iCheck/icheck.js" type="text/javascript"></script>
        <script src="AdminLTE-master/plugins/sweet-alert/dist/sweetalert-dev.js" type="text/javascript"></script>

        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="AdminLTE-master/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="AdminLTE-master/dist/css/AdminLTE.css" />
        <link rel="stylesheet/less" type="text/css" href="AdminLTE-master/build/less/AdminLTE.less" />
        <link rel="stylesheet" href="AdminLTE-master/plugins/sweet-alert/dist/sweetalert.css" />
        <link rel="stylesheet" href="AdminLTE-master/plugins/sweet-alert/themes/facebook/facebook.css" />

        <link rel="stylesheet" href="services/css-login/style.css" />

        <style>
            html { 
                margin:0;
                padding:0;
                background: url(services/img/img_back_1.jpg) no-repeat fixed; 
                -webkit-background: url(services/img/img_back_2_1.jpg) no-repeat fixed; 
                -webkit-background-size: cover; /* pour anciens Chrome et Safari */
                background-size: cover; /* version standardisée */
                overflow-y: auto;

            }
            .login-page{
                background: inherit;
            }
            .login-box {
                margin-top: 50vh; /* poussé de la moitié de hauteur de viewport */
                transform: translateY(-60%); /* tiré de la moitié de sa propre hauteur */
                -webkit-backface-visibility: hidden;
                margin-bottom: 0px;
                -webkit-margin-bottom: 0px;
            }
            .login-box-body{
                background: rgba(255,255,255,0.1);
                box-shadow: 0px 0px 20px #fff;
                -webkit-box-shadow: 0px 0px 20px #fff;
            }
            .login-box-msg{
                font-weight: bold;
                color: #fff;
            }
            .login-logo a{
                color: white;
            }
            .login-box-body a{
                color: black;
                font-weight: bold;
            }
            .login-box-body a:hover{
                color: white;
                text-decoration: underline;
            }
            .form-control{
                background: rgba(255,255,255,0.6); 
                color : black;
                font-weight: bold;
            }
        </style>
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href=""><b>Entrée</b> Back-office</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Connectez vous pour continuer</p>
                <form action="" method="post" id="connection-form">
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Login" type="text" name="str_LOGIN" id="str_LOGIN" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Mot de passe" type="password" name="str_PASSWORD" id="str_PASSWORD" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <input class="form-control" type="hidden" id="findUser" name="findUser">
                    <div class="row">
                        <!--div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label class="">
                                    <div aria-disabled="false" aria-checked="false" style="position: relative;" class="icheckbox_square-blue"><input style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;" type="checkbox"><ins style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;" class="iCheck-helper"></ins></div> Remember Me
                                </label>
                            </div>
                        </div--><!-- /.col -->
                        <div class="col-xs-4 pull-right">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Connexion</button>
                        </div><!-- /.col -->
                    </div>
                    <div class="row" id="message-info">
                        <p style="color: white;font-weight: bold;width: 100%;height: 10px;margin: auto;text-align: center">Novembre 2018 | powered by <a href="mailto:morokojeanr@hotmail.com">JMO</a></p>
                    </div>
                </form>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
                $('#connection-form').submit(function (e) {
                    e.preventDefault();
                    var str_LOGIN = $('#connection-form #str_LOGIN').val();
                    var str_PASSWORD = $('#connection-form #str_PASSWORD').val();
                    //var task = $('#connection-form #findUser').val();
                    //
                    $('#message-info p').html('<i class="fa fa-refresh fa-spin"></i>');
                    $.ajax({
                        url: 'composant/com_security/controlersecurity.php', // La ressource ciblée
                        type: 'POST', // Le type de la requête HTTP.
                        data: 'task=connexion&str_LOGIN=' + str_LOGIN + '&str_PASSWORD=' + str_PASSWORD,
                        dataType: 'text',
                        success: function (response) {
                            //alert(json);return;
                            var obj = $.parseJSON(response);
                            if (obj[0].code_statut == "1")
                            {
                                //$('#message-info').fadeIn(300).html("Svp Entrez le Password ! ");
                                $('#message-info p').empty();
                                $('#message-info p').fadeIn(300).text("Connexion effectuée");
                                $('#messageinfo').fadeOut(5000);
//                                var newURL = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname;
                                //var URL = window.location.href
                                //window.location.replace(URL);
                                window.location.href = "index.php";
                            } else {
                                //alert(obj[0].results);
                                swal({
                                    title: "Echec de l'opéraion",
                                    text: obj[0].results,
                                    type: "error",
                                    confirmButtonText: "Ok"
                                });
                                $('#message-info p').empty();
                            }
                        }
                    });
                });
            });
        </script>
    </body></html>

    <?php
}
else
    header("location:index.php");
?>
