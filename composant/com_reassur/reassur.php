<?php

class Reassur {

    static function showAllReassur() {
        ?>
        <script src="composant/com_reassur/reassur.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li> <li>Réassurance</li>  <li>Liste</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des contrat réassuré
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
                                <td>Contrat</td>
                                <td>Réassureur</td>
                                <td>Par SUNU</td>
                                <td>Par réassureur</td>
                                <td>Actions</td>
                                <td></td>
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
                                    <label for="str_NUMERO_POLICE" class="col-sm-4 control-label">Production <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_NUMERO_POLICE" id="str_NUMERO_POLICE" style="width: 100%" class="getAvenant">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_AVENANT" class="col-sm-4 control-label">Avenant <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="int_AVENANT" id="int_AVENANT" style="width: 100%">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_REASSUREUR_ID" class="col-sm-4 control-label">Réassureur <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_REASSUREUR_ID" id="str_REASSUREUR_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PAR_SUNU" class="col-sm-4 control-label">Par de SUNU (en %)<span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PAR_SUNU" name="int_PAR_SUNU" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PAR_REASSUREUR" class="col-sm-4 control-label">Par du réassureur (en %)<span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PAR_REASSUREUR" name="int_PAR_REASSUREUR" placeholder="" type="number" required="">
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
                                    <label for="str_PRODUCTION_ID_EDIT" class="col-sm-4 control-label">Production <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_PRODUCTION_ID_EDIT" id="str_PRODUCTION_ID_EDIT" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_REASSUREUR_ID_EDIT" class="col-sm-4 control-label">N° polices <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_REASSUREUR_ID_EDIT" id="str_REASSUREUR_ID_EDIT" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PAR_SUNU_EDIT" class="col-sm-4 control-label">Par de SUNU <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PAR_SUNU_EDIT" name="int_PAR_SUNU_EDIT" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PAR_REASSUREUR_EDIT" class="col-sm-4 control-label">Par du réassureur <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PAR_REASSUREUR_EDIT" name="int_PAR_REASSUREUR_EDIT" placeholder="" type="number" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_REASSUR_ID" id="str_REASSUR_ID" />
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
