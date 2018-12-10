<?php
if(!isset($_SESSION))
    session_start();
if(isset($_SESSION['str_SECURITY_ID']) && !empty($_SESSION['str_SECURITY_ID']))
{
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SUNU | Centralize data build</title>

        <script src="services/plugins/jquery/dist/jquery.min.js"></script>
        <link href="services/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="services/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet">
        <link href="services/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!--<link href="services/plugins/pace/pace.css" rel="stylesheet">
        <link href="services/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">-->
        <link rel="stylesheet" href="services/plugins/nano-scroll/nanoscroller.css">
        <!-- Debut Insertion des bibliotheque de champs de recherche de liste deroulante et des data -->
        <link rel="stylesheet" type="text/css" href="services/css/select2/select2.min.css"/>
        
        <link rel="stylesheet" type="text/css" href="services/css/select-bootstrap/select2-bootstrap.min.css"/>
        <!--Fin d'insertion de la bibliothèque select2 -->
        <script src="services/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <!--script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script-->
        <script src="services/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
        
        <link rel="stylesheet" href="services/plugins/select2/select2.css" />
        
        <link rel="stylesheet" href="services/plugins/datatables/jquery.dataTables.css" />
        <link href="services/plugins/toast/jquery.toast.min.css" rel="stylesheet">
        <!--template css-->
        <link href="services/css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href=""/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- fin data title-->
        <script src="services/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <!--script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script-->
        <script src="services/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

        <link rel="stylesheet" href="services/plugins/select2/select2.css" />

        <link rel="stylesheet" href="services/plugins/datatables/jquery.dataTables.css" />
        <link rel="stylesheet" href="services/plugins/datatables/dataTables.bootstrap.css" />
        <script src="services/js/jquery.session.js"></script>
        <link rel="stylesheet" type="text/css" href="services/css/jquery.datetimepicker.css"/>
        <!--end datepicker -->
        <script src="services/plugins/select2/select2.full.js" type="text/javascript"></script>
        <script src="services/plugins/select2/i18n/fr.js" type="text/javascript"></script>

        <script src="services/js/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="services/css/sweetalert.css">
        <script src="./services/js/bootstrap-notify.min.js"></script>
        <link rel="stylesheet" type="text/css" href="services/css/site-custome.css">
        <link href="services/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="services/plugins/nano-scroll/nanoscroller.css">
        <link rel="stylesheet" href="services/plugins/metisMenu/metisMenu.min.css">
        <!--<link rel="stylesheet" href="services/services/plugins/jquery-steps/jquery-steps.css">-->
        <link rel="stylesheet" type="text/css" href="services/css/sites.css">
        <script src="services/js/angularJs.js"></script>
        <script src="services/js/angular/angular-datatables.min.js"></script>
        <link rel="shortcut icon" href="favicon.ico" />
        <!-- session var -->

        <!--- permet de recharger la page de manière automatique <meta http-equiv="refresh" content="3600">-->
    </head>

    <!--<body oncontextmenu="return false;" onselectstart="return false" ondragstart="return false">-->
    <body >
        <div class="theme-loader">
            <div class="loader-track">
                <div class="loader-bar"></div>
            </div>
        </div>
        <!--top bar start-->
        <div class="top-bar light-top-bar"><!--by default top bar is dark, add .light-top-bar class to make it light-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-6">
                        <a href="" class="admin-logo">
                            <h1><img src="services/img/logo.png" alt="Logo Quick Order" width="150" height="80"></h1>
                        </a>
                        <div class="left-nav-toggle visible-xs visible-sm">
                            <a href="#">
                                <i class="glyphicon glyphicon-menu-hamburger"></i>
                            </a>
                        </div><!--end nav toggle icon-->
                    </div>
                    <div class="col-xs-6">
                        <?php
                            include_once('topmenu.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- top bar end-->
        <!--left navigation start-->
        <aside class="float-navigation light-navigation">
            <?php
                include('menu.php');
            ?>
        </aside>
        <!--left navigation end-->


        <!--main content start-->
        <section class="main-content container">
            <!--start page content-->
            <div class="row" id="contenue-application">

            </div>
            <!-- End content -->
        </section>
        <!--end main content-->
        <!--Common plugins-->
       
        <script src="services/js/jquery-ui.min.js"></script>
        <script src="services/plugins/bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="services/plugins/pace/pace.min.js"></script>
        <script src="services/plugins/jasny-bootstrap/services/js/jasny-bootstrap.min.js"></script>-->
        <script src="services/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="services/plugins/nano-scroll/jquery.nanoscroller.min.js"></script>
        <script src="services/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="services/js/float-custom.js"></script>
        <!-- iCheck for radio and checkboxes -->
        <script src="services/plugins/iCheck/icheck.min.js"></script>
        <!-- Message d'alert en vert -->
        <script src="services/plugins/toast/jquery.toast.min.js"></script>

        <!-- FIN Insertion des bibliotheque de champs de recherche dans une liste deroulante et des data -->
        <script src="services/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="services/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="services/js/build/jquery.datetimepicker.full.js"></script>
        <!-- Datatables-->
        <script src="services/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="services/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="services/js/select2/select2.full.js"></script>
        <script src="services/js/select2/bootstrap.min.js"></script>
        <script src="services/js/select2/anchor.min.js"></script>
        <script>

            // getAllNotify();
            //getListeOfNotify();
			$.datetimepicker.setLocale('fr');
            $("#setApZeroNumberOfNotify").on("hover", function () {
                $("#nbr_notification").val(0);
                $("#add_nbr_notification").html(0);
            });

            $.ajax("./services/center.php" + "?task=showHomeAdminPage")
                    .done(function (data, status, jqxhr) {
                        $('#contenue-application').empty();
                        $('#contenue-application').append(jqxhr.responseText).fadeIn();
                    });

            $('a.m_link').on('click', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var task = $btn.attr('id');
                var content_top_number = "";
                //$.session.set("url_page", task);//en lien avec les boutons a afficher par pages

                $.ajax("./services/center.php" + "?task=" + task)
                    .done(function (data, status, jqxhr) {
                        $('#contenue-application').empty();
                        $('#contenue-application').html(jqxhr.responseText).fadeIn();
                        haveSession();
                    });
            });

            $(document).ready(function () {
                $('#datatable').dataTable();
            });
            anchors.options.placement = 'left';
            anchors.add('.container h1, .container h2, .container h3, .container h4, .container h5');

            $.fn.select2.defaults.set("theme", "bootstrap");

            var placeholder = "Selectionner un élément";

            $(".select2-single, .select2-multiple, .select2me").select2({
                placeholder: placeholder,
                width: null,
                containerCssClass: ':all:'
            });
            $(document).ready(function () {
                $.ajax("./services/center.php" + "?task=showAllMenu")
                        .done(function (data, status, jqxhr) {
                            $('#IdgetMenu').empty();
                            $('#IdgetMenu').append(jqxhr.responseText).fadeIn();
                        });

            });
            
             $("#deconnexion").on('click', function(e){
                e.preventDefault();
                swal({
                    title: 'Demande de Confirmation',
                    text: "Etes-vous sûr de vouloir vous déconnecter ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff5858',
                    cancelButtonColor: '#fe4500',
                    confirmButtonText: 'Oui',
                    cancelButtonText: 'Non',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        window.location.href = "composant/com_security/controlersecurity.php?task=disconnect";
                    } else {
                        swal(
                            'Annulation',
                            'Opération annulé',
                            'error'
                        );
                    }
                });
            });

            function haveSession()
            {
                $.get("services/session.php", function(json, e){
                    if(json != "")
                    {
                        let obj = $.parseJSON(json);
                        if(obj[0].code_statut = "-1")
                        {
                            //swal("Votre session à expiré, veuillez vous reconnecter s'il vous plait.");
                            window.location.href = "composant/com_security/controlersecurity.php?task=disconnect";
                        }
                    }
                });
            }
            
        </script>

        <script src="services/js/float-custom.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(".theme-loader").remove();
            });
            $('.date-picker').datetimepicker({
                onGenerate:function( ct ){
                    $(this).find('.xdsoft_date')
                        .toggleClass('xdsoft_disabled');
                },
                minDate:'-<?= date("Y/m/d", strtotime("-7 day")); ?>',
                maxDate:'+1970/01/2',
                timepicker:false,
                format:'Y/m/d'
            });
            $('.date_timepicker_start').datetimepicker({
                format:'Y-m-d',
                onShow:function( ct ){
                    this.setOptions({
                        maxDate:jQuery('.date_timepicker_end').val()?jQuery('.date_timepicker_end').val():false
                    })
                },
                timepicker:false
            });
            $('.date_timepicker_end').datetimepicker({
                format:'Y-m-d',
                onShow:function( ct ){
                    this.setOptions({
                        minDate:jQuery('.date_timepicker_start').val()?jQuery('.date_timepicker_start').val():false
                    })
                },
                timepicker:false
            });
        </script>

        <!-- fichier graph 2
        <script type="text/javascript" src="./services/highcharts2/js/highcharts.js"></script>
        <script type="text/javascript" src="./services/highcharts2/js/modules/exporting.js"></script>-->
        <!-- hitchar6 -->
        <script src="./services/Highcharts6.2.0/code/highcharts.js"></script>
        <script src="./services/Highcharts6.2.0/code/modules/exporting.js"></script>-->
        <script src="./services/Highcharts6.2.0/code/modules/export-data.js"></script>
        <!-- fin fichier graph -->
    </body>
</html>
<?php
}
else
    header("location:login.php");
?>
