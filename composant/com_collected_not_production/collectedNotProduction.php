<?php

class CollectedNotProduction {

    static function showAllCollectedNotProduction() {
        ?>
        <script src="composant/com_collected_not_production/collectedNotProduction.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li>  <li>Encaissement</li> <li>Encaissement sans production</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste encaissement perçu sans production
                    <button type="button" class="pull-right btn btn-danger btn-outline btn-sm" id="modal_extract_key" data-toggle="modal">
                        <i class="fa fa-file-excel-o"></i> Extraire
                    </button>
                </div>
                <div class="panel-body" style="overflow-x: auto">
                    <table class="table table-striped table-hover clo" id="examples" >
                        <thead>
                        <tr>
                            <td>Dates de saisies</td>
                            <td>Lettrages</td>
                            <td>Polices</td>
                            <td>Montants</td>
                            <td>Dates effet</td>
                            <td>Dates échéance</td>
                            <!--<td>Actions</td>-->
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
                        <h4 class="modal-title">Ajouter lettrage</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="add_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="str_CONTRACT_ID" class="col-sm-4 control-label">Police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_CONTRACT_ID" id="str_CONTRACT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_INTERMEDIATE_ID" class="col-sm-4 control-label">Intermédiaire <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_INTERMEDIATE_ID" id="str_INTERMEDIATE_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_NUMERO_POLICE" class="col-sm-4 control-label">Numéro de police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_NUMERO_POLICE" name="str_NUMERO_POLICE" placeholder="" type="text" required="" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC" class="col-sm-4 control-label">Montant <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_TTC" name="int_PRIME_TTC" placeholder="Partenaire" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_EFFET" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_EFFET" name="dt_EFFET" placeholder="" type="text" required="" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_ECHEANCE" name="dt_ECHEANCE" placeholder="" type="text" required="" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_COMMISSION" class="col-sm-4 control-label">Commission <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_COMMISSION" name="int_COMMISSION" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_NET" class="col-sm-4 control-label">Prime NET <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_NET" name="int_PRIME_NET" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_TAUX_COMMISSION" class="col-sm-4 control-label">Taux de commission <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_TAUX_COMMISSION" name="int_TAUX_COMMISSION" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_MAJO" class="col-sm-4 control-label">Majoration :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_MAJO" name="int_MAJO" placeholder="" type="number" min="0" required="">
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
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="info_POLICE">Modifier</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="edit_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="row">
                                    <table class="table table-striped">
                                        <caption class="caption" >
                                            Rappel des information du contrat
                                        </caption>
                                        <thead>
                                        <tr>
                                            <th>date effet</th>
                                            <th>date echeance</th>
                                            <th>Prime total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td id="td_EFFET"></td>
                                            <td id="td_ECHEANCE"></td>
                                            <td id="PRIME_TOTAL"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row encaissement">
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-scrollable-borderless" id="examples-encaissement">
                                            <caption class="caption" >
                                                Liste des paiements associé
                                            </caption>
                                            <thead>
                                                <tr>
                                                    <th>Dates d'encaissements</th>
                                                    <th>Numéros léttrage</th>
                                                    <th>Date effets</th>
                                                    <th>Date echéances</th>
                                                    <th>Montants</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-default pull-right" data-dismiss="modal">Fermer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal modal-success fade" id="modal_extract_key" role="dialog">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ajouter</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="extract_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="str_NAME" class="col-sm-4 control-label">Nom du fichier <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_FILE_NAME" name="str_FILE_NAME" placeholder="Nom du fichier" type="text" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="services/document_uploader/sinistre_traite.xls" id="download" class="hidden btn btn-default"><i class="fa fa-download"></i>Telecharger</a>
                            <button type="submit" class="btn btn-warning pull-right" id="saved" style="margin-left: 3px;">Enregistrer</button>
                            <button type="reset" class="btn btn-default pull-right" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php

    }

}
