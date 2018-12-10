<?php
    class Dashbord{
        public static function showHomeAdminPage()
        {
?>
            <script src="composant/com_dashbord/dashbord.js"></script>
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Récapitulatif d'activitées</h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <ol class="breadcrumb">
                            <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                            <li>Tableau de bord</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                    <div id="pie"></div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                    <div id="statisticals"></div>
                </div>
            </div> <br>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                    <div id="production"></div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
                    <div id=""></div>
                </div>
            </div>

<?php
        }
    }
?>