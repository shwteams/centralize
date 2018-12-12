<?php

class Collection {

    static function showAllCollection() {
        ?>
        <script src="composant/com_collection/collection.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li> <li>Encaissement</li>  <li>Liste</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des encaissements
                    <div class="pull-right">
                        <button type="button" class="btn btn-default btn-outline btn-sm" id="modal_add_file" data-toggle="modal">
                            <i class="fa fa-file-excel-o"></i> Intégration
                        </button>
                    </div>
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
                                <td>Dates de saisies</td>
                                <td>Lettrages</td>
                                <td>Polices</td>
                                <td>Montants</td>
                                <td>Dates effet</td>
                                <td>Dates échéance</td>
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
        <div class="modal modal-success fade" id="modal_add_file" role="dialog">
            <div class="modal-dialog ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Veuillez sélectionner un CSV ayant l'entête à la ligne 1</h4>
                    </div> 
                    <form class="form-horizontal" role="form" id="add_file_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-xs-12 col-xs-offset-2" >
                                        <input type="file" accept=".csv" name="str_ILLUSTRATION" class="str_ILLUSTRATION" data-buttonText="Charger fichier utilisateurs" data-buttonName="btn-primary" data-iconName="glyphicon glyphicon-inbox" data-buttonBefore="true" data-placeholder="Aucun fichier selectionné" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_LETTRAGE_FILE" class="col-sm-4 control-label">Lettrage <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_LETTRAGE_FILE" id="str_LETTRAGE_FILE" class="ma_liste" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_NUMERO_POLICE_FILE" class="col-sm-4 control-label">Numéro de police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_NUMERO_POLICE_FILE" id="str_NUMERO_POLICE_FILE" class="ma_liste" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC_FILE" class="col-sm-4 control-label">Montant <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="int_PRIME_TTC_FILE" id="int_PRIME_TTC_FILE" class="ma_liste" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_EFFET_FILE" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="dt_EFFET_FILE" id="dt_EFFET_FILE" class="ma_liste" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE_FILE" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="dt_ECHEANCE_FILE" id="dt_ECHEANCE_FILE" class="ma_liste" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="addLettrageXLS" id="addLettrageXLS">
                            <button type="submit" class="btn btn-warning pull-right" id="saved" style="margin-left: 3px;">Enregistrer</button>
                            <button type="reset" class="btn btn-default pull-right" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
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
                                    <label for="str_LETTRAGE" class="col-sm-4 control-label">Lettrage <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_LETTRAGE" name="str_LETTRAGE" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_NUMERO_POLICE" class="col-sm-4 control-label">Numéro de police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_NUMERO_POLICE" name="str_NUMERO_POLICE" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC" class="col-sm-4 control-label">Montant <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_TTC" name="int_PRIME_TTC" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_EFFET" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_EFFET" name="dt_EFFET" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_ECHEANCE" name="dt_ECHEANCE" placeholder="" type="text" required="">
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
                                    <label for="str_LETTRAGE_EDIT" class="col-sm-4 control-label">Lettrage <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_LETTRAGE_EDIT" name="str_LETTRAGE_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_NUMERO_POLICE_EDIT" class="col-sm-4 control-label">Numéro de police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_NUMERO_POLICE_EDIT" name="str_NUMERO_POLICE_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC_EDIT" class="col-sm-4 control-label">Montant <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_TTC_EDIT" name="int_PRIME_TTC_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_EFFET_EDIT" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_EFFET_EDIT" name="dt_EFFET_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE_EDIT" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_ECHEANCE_EDIT" name="dt_ECHEANCE_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_COLLECTION_ID" id="str_COLLECTION_ID" />
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
