<?php

class etats {
    static function showAlletats() {
        ?>
        <script src="composant/com_etats/etats.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li> <li>Configuration</li>  <li>Etat</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des Etats
                    <div class="pull-right">
                        <button type="button" class="btn btn-danger btn-outline btn-sm" id="modal_add_key" data-toggle="modal">
                            <i class="fa fa-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover clo" id="examples" >
                        <thead>
                            <tr>
                                <td>Libelles</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal modal-success fade" id="modal_add_key" role="dialog">
            <div class="modal-dialog ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajouter</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="add_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="str_LIBELLE" class="col-sm-4 control-label">Libelle <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_LIBELLE" name="str_LIBELLE" placeholder="Etat" type="text" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning pull-right" id="saved" style="margin-left: 3px;">Enregistrer</button>
                            <button type="reset" class="btn btn-default pull-right" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal modal-success fade" id="modal_edit_key" role="dialog">
            <div class="modal-dialog ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modifier</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="edit_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="str_LIBELLE_EDIT" class="col-sm-4 control-label">Libelle <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_LIBELLE_EDIT" name="str_LIBELLE_EDIT" placeholder="Etat" type="text" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_ETAT_ID" id="str_ETAT_ID" />
                            <button type="submit" class="btn btn-warning pull-right" id="saved_edit" style="margin-left: 3px;">Enregistrer</button>
                            <button type="reset" class="btn btn-default pull-right" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php

    }

}
