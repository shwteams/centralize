<?php

class Contract {

    static function showAllContract() {
        ?>
        <script src="composant/com_contract/contract.js"></script>
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Récapitulatif d'activitées</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb">
                        <li><a href="javascript: void(0);"><i class="fa fa-home"></i></a></li>
                        <li>Tableau de bord</li>  <li>Acquisitions</li> <li>Contrats</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Liste des Contrats
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
                                <td>Numéros de polices</td>
                                <td>Etats</td>
                                <td>Motif</td>
                                <td>Nombres</td>
                                <td>Produits</td>
                                <td>Branches</td>
                                <td>Clients</td>
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
                                    <label for="str_CUSTOMER_ID" class="col-sm-4 control-label">Client <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_CUSTOMER_ID" id="str_CUSTOMER_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_PLUGGED_ID" class="col-sm-4 control-label">Branche <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_PLUGGED_ID" id="str_PLUGGED_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="str_PRODUIT_ADD">
                                    <label for="str_PRODUIT_ID" class="col-sm-4 control-label">Produit <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_PRODUIT_ID" id="str_PRODUIT_ID" style="width: 100%">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_ETAT" class="col-sm-4 control-label">Etat :</label>
                                    <div class="col-sm-8">
                                        <select name="str_ETAT" id="str_ETAT" style="width: 100%">
                                            <option value="on">Actif</option>
                                            <option value="off">Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_POLICENUMBER" class="col-sm-4 control-label">Numéro police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_POLICENUMBER" name="str_POLICENUMBER" placeholder="AUX25002" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_NUNBERCAR" class="col-sm-4 control-label target">Nombre :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control target" id="int_NUNBERCAR" name="int_NUNBERCAR" placeholder="Nombre de véhicule" type="number" min="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_MOTIF" class="col-sm-4 control-label target">Motif :</label>
                                    <div class="col-sm-8">
                                        <textarea class="target" name="str_MOTIF" id="str_MOTIF" cols="50" rows="20" placeholder="Motif"></textarea>
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
                                    <label for="str_CUSTOMER_EDIT_ID" class="col-sm-4 control-label">Client <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_CUSTOMER_EDIT_ID" id="str_CUSTOMER_EDIT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_PLUGGED_EDIT_ID" class="col-sm-4 control-label">Branche <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_PLUGGED_EDIT_ID" id="str_PLUGGED_EDIT_ID" style="width: 100%">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="str_PRODUIT_EDIT_ADD">
                                    <label for="str_PRODUIT_EDIT_ID" class="col-sm-4 control-label">Produit <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <select name="str_PRODUIT_EDIT_ID" id="str_PRODUIT_EDIT_ID" style="width: 100%">

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_ETAT_EDIT" class="col-sm-4 control-label">Etat :</label>
                                    <div class="col-sm-8">
                                        <select name="str_ETAT_EDIT" id="str_ETAT_EDIT" style="width: 100%">
                                            <option value="on">Actif</option>
                                            <option value="off">Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_POLICENUMBER_EDIT" class="col-sm-4 control-label">Numéro police <span class="require">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="str_POLICENUMBER_EDIT" name="str_POLICENUMBER_EDIT" placeholder="AUX25002" type="text" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="int_NUNBERCAR_EDIT" class="col-sm-4 control-label target">Nombre :</label>
                                    <div class="col-sm-8">
                                        <input class="form-control target" id="int_NUNBERCAR_EDIT" name="int_NUNBERCAR_EDIT" placeholder="Nombre de véhicule" type="number" min="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="str_MOTIF_EDIT" class="col-sm-4 control-label target">Motif :</label>
                                    <div class="col-sm-8">
                                        <textarea class="target" name="str_MOTIF_EDIT" id="str_MOTIF_EDIT" cols="50" rows="20" placeholder="Motif"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="str_CONTRACT_EDIT_ID" id="str_CONTRACT_EDIT_ID" />
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
