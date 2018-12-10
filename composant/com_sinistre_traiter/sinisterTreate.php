<?php

class SinisterTreate {
    static function showAllSinisterTreate() {
        ?>
        <script src="composant/com_sinistre_traiter/sinisterTreate.js"></script>
        <!--<script src="composant/com_sinister/angular-sinister.js"></script>-->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li> <li>Sinistre</li>  <li>Liste</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des sinistres traité
                    <!--<div class="pull-right">
                        <button type="button" class="btn btn-danger btn-outline btn-sm" id="modal_add_key" data-toggle="modal">
                            <i class="fa fa-plus"></i> Ajouter
                        </button>
                    </div>-->
                </div>
                <div class="panel-body" style="overflow: auto;">
                    <table class="table table-striped table-hover clo" id="examples">
                        <thead>
                            <tr>
                                <td>Gestionnaires</td>
                                <td>Numéros de polices</td>
                                <td>Numéros de sinistres</td>
                                <td>Dernière modifications</td>
                                <td>Dates de survenance</td>
                                <!--<td>Dates de traitements</td>-->
                                <td>Boni</td>
                                <td>Mali</td>
                                <td>Boni potentiel</td>
                                <!--<td>Cumulé payé</td>-->
                                <td>Provisions</td>
                                <!--<td>Provisions fins</td>-->
                                <td>Etats</td>
                                <td>Autres observations</td>
                                <td>Actions</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody >

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal modal-success fade" id="modal_editing_key" role="dialog">
            <div class="modal-dialog ">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Saisie de bonie ou mali</h4>
                    </div>
                    <form class="form-horizontal" role="form" id="editing_key_form">
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="str_CONTRAT_EDITING_ID" class="col-sm-4 control-label">Numéro de police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_CONTRAT_EDITING_ID" id="str_CONTRAT_EDITING_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_SURVENANCE_EDITING" class="col-sm-4 control-label">Date de survenance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date-picker" id="dt_SURVENANCE_EDITING" name="dt_SURVENANCE_EDITING" placeholder="2018/11/07" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_NUMERO_SINISTRE_EDITING" class="col-sm-4 control-label">Numéro de sinistre <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_NUMERO_SINISTRE_EDITING" name="str_NUMERO_SINISTRE_EDITING" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_TRAITEMENT_EDITING" class="col-sm-4 control-label">Date de traitement <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date-picker" id="dt_TRAITEMENT_EDITING" name="dt_TRAITEMENT_EDITING" placeholder="2018/11/07" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mt_EVAL_EDITING" class="col-sm-4 control-label">Montant éval <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="mt_EVAL_EDITING" name="mt_EVAL_EDITING" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mt_CUMULE_PAYE_EDITING" class="col-sm-4 control-label">Cummulé payé <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="mt_CUMULE_PAYE_EDITING" name="mt_CUMULE_PAYE_EDITING" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="mt_PROVISION_EDITING" class="col-sm-4 control-label">Provision <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="mt_PROVISION_EDITING" name="mt_PROVISION_EDITING" placeholder="" type="number" required="">
                                    </div>
                                </div>

                                <!-- debut -->
                                <div class="form-group">
                                    <label for="int_BONI" class="col-sm-4 control-label">Boni <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_BONI" name="int_BONI" placeholder="" type="number" min="0 required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_BONI_POTENTIEL" class="col-sm-4 control-label">Boni potentiel <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_BONI_POTENTIEL" name="int_BONI_POTENTIEL" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_MALI" class="col-sm-4 control-label">Mali <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_MALI" name="int_MALI" placeholder="" type="number" min="0 required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_OBSERVATION" class="col-sm-4 control-label">Autre observation <span class="require"></span> :</label>
                                    <div class="col-sm-8">
                                        <textarea name="str_OBSERVATION" id="str_OBSERVATION" cols="30" rows="10"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="str_AUTRE_OBSERVATION_ID" class="col-sm-4 control-label">Observation <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_AUTRE_OBSERVATION_ID" id="str_AUTRE_OBSERVATION_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_ETAT_ID" class="col-sm-4 control-label">Etat <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_ETAT_ID" id="str_ETAT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- fin -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_SINISTER_EDITING_ID" id="str_SINISTER_EDITING_ID" />
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
