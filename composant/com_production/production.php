<?php

class Production {

    static function showAllProduction() {
        ?>
        <script src="composant/com_production/production.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li>  <li>Production</li> <li>Liste</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des productions
                    <div class="pull-right">
                        <button type="button" class="btn btn-danger btn-outline btn-sm" id="modal_add_key" data-toggle="modal">
                            <i class="fa fa-plus"></i> Ajouter
                        </button>
                    </div>
                </div>
                <div class="panel-body" style="overflow-x: auto">
                    <table class="table table-striped table-hover clo" id="examples" >
                        <thead>
                            <tr>
                                <td>Numéros de police</td>
                                <td>Dates effet</td>
                                <td>Dates echéance</td>
                                <td>Primes TTC</td>
                                <td>Commissions</td>
                                <td>Primes net</td>
                                <td>Taux comission</td>
                                <td>Avenant</td>
                                <td>Majo</td>
                                <td>Intermediaire</td>
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
                                    <label for="dt_EFFET" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_EFFET" name="dt_EFFET" placeholder="20/07/2018" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_ECHEANCE" name="dt_ECHEANCE" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC" class="col-sm-4 control-label">Prime TTC <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_TTC" name="int_PRIME_TTC" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_COMMISSION" class="col-sm-4 control-label">Commission <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_COMMISSION" name="int_COMMISSION" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_NET" class="col-sm-4 control-label">Prime NETTE <span class="require">*</span> :</label>
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
                                <!--<div class="form-group">
                                    <label for="int_AVENANT" class="col-sm-4 control-label">Avenant :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_AVENANT" name="int_AVENANT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>-->
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
                                    <label for="str_CONTRACT_EDIT_ID" class="col-sm-4 control-label">Police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_CONTRACT_EDIT_ID" id="str_CONTRACT_EDIT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_INTERMEDIATE_EDIT_ID" class="col-sm-4 control-label">Intermédiaire <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_INTERMEDIATE_EDIT_ID" id="str_INTERMEDIATE_EDIT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_EFFET_EDIT" class="col-sm-4 control-label">Date effet <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_EFFET_EDIT" name="dt_EFFET_EDIT" placeholder="20/07/2018" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ECHEANCE_EDIT" class="col-sm-4 control-label">Date échéance <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_ECHEANCE_EDIT" name="dt_ECHEANCE_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_TTC_EDIT" class="col-sm-4 control-label">Prime TTC <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_TTC_EDIT" name="int_PRIME_TTC_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_COMMISSION_EDIT" class="col-sm-4 control-label">Commission <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_COMMISSION_EDIT" name="int_COMMISSION_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_PRIME_NET_EDIT" class="col-sm-4 control-label">Prime NETTE <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_PRIME_NET_EDIT" name="int_PRIME_NET_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_TAUX_COMMISSION_EDIT" class="col-sm-4 control-label">Taux de commission <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_TAUX_COMMISSION_EDIT" name="int_TAUX_COMMISSION_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label for="int_AVENANT_EDIT" class="col-sm-4 control-label">Avenant :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_AVENANT_EDIT" name="int_AVENANT_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <label for="int_MAJO_EDIT" class="col-sm-4 control-label">Majoration :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_MAJO_EDIT" name="int_MAJO_EDIT" placeholder="" type="number" min="0" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_PRODUCTION_ID" id="str_PRODUCTION_ID" />
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
