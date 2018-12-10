<?php

class CompagnyContract {

    static function showAllCompagnyContract() {
        ?>
        <script src="composant/com_company_contract/companyContract.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li> <li>Acquisition</li>  <li>Contrat entreprise</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des contracts entreprises
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
                                <td>N° de polices</td>
                                <td>Dates d'entrées</td>
                                <td>Dates de sorties</td>
                                <td>Immatriculations</td>
                                <td>Code de regulation</td>
                                <td>Nombre de places</td>
                                <td>Valeur à neuf</td>
                                <td>Poid en Kg</td>
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
                                    <label for="str_NUMERO_POLICE" class="col-sm-4 control-label">N° polices <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_NUMERO_POLICE" id="str_NUMERO_POLICE" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ENTREE" class="col-sm-4 control-label">Date entrée <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_ENTREE" name="dt_ENTREE" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_SORTIE" class="col-sm-4 control-label">Date de sortie <span class="require"></span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_SORTIE" name="dt_SORTIE" placeholder="" type="text" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_IMMATRICULATION" class="col-sm-4 control-label">Immatriculation <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_IMMATRICULATION" name="str_IMMATRICULATION" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_CODE_REGULATION" class="col-sm-4 control-label">Code régulation <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_CODE_REGULATION" name="str_CODE_REGULATION" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_NBRPLACE" class="col-sm-4 control-label">Nombre de place <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_NBRPLACE" name="int_NBRPLACE" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_VALEUR" class="col-sm-4 control-label">Valeur à neuf <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_VALEUR" name="int_VALEUR" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_POID" class="col-sm-4 control-label">Poid en Kg <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_POID" name="int_POID" placeholder="" type="number" required="">
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
                                    <label for="str_NUMERO_POLICE_EDIT" class="col-sm-4 control-label">N° polices <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_NUMERO_POLICE_EDIT" id="str_NUMERO_POLICE_EDIT" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_ENTREE_EDIT" class="col-sm-4 control-label">Date entrée <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_start" id="dt_ENTREE_EDIT" name="dt_ENTREE_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dt_SORTIE_EDIT" class="col-sm-4 control-label">Date de sortie <span class="require"></span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control date_timepicker_end" id="dt_SORTIE_EDIT" name="dt_SORTIE_EDIT" placeholder="" type="text" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_IMMATRICULATION_EDIT" class="col-sm-4 control-label">Immatriculation <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_IMMATRICULATION_EDIT" name="str_IMMATRICULATION_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_CODE_REGULATION_EDIT" class="col-sm-4 control-label">Code régulation <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_CODE_REGULATION_EDIT" name="str_CODE_REGULATION_EDIT" placeholder="" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_NBRPLACE_EDIT" class="col-sm-4 control-label">Nombre de place <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_NBRPLACE_EDIT" name="int_NBRPLACE_EDIT" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_VALEUR_EDIT" class="col-sm-4 control-label">Valeur à neuf <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_VALEUR_EDIT" name="int_VALEUR_EDIT" placeholder="" type="number" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_POID_EDIT" class="col-sm-4 control-label">Poid en Kg<span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="int_POID_EDIT" name="int_POID_EDIT" placeholder="" type="number" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_CONTRAT_ENTREPRISE_ID" id="str_CONTRAT_ENTREPRISE_ID" />
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
