<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Security {
    static function showLogout(){
        ?>
        <script src="composant/com_security/logout.js" type="text/javascript"></script
        <?php
    }

    static function showUserAccount() {
        /* include_once 'menuinfos.php'; */
        ?>
        <script src="composant/com_security/security.js" type="text/javascript"></script>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="index.php">Tableau de bord</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="javascript:;">Liste des utilisateurs</a>
                </li>
            </ul>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <h2 class="page-title">
                Mon compte
                <small>Mise à jour du compte utilisateur</small>
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 col-md-offset-5 col-lg-offset-5 col-sm-offset-5 col-xs-offset-4">
                <img src="" class="rounded mx-auto d-block img-circle img-responsive img-thumbnail" alt="" id="str_ILLUSTRATION_ID">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="edit_user">
                <form role="form" id="edit-key-form-user">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_LOGIN" class="control-label">
                                Nom d'utilisateur <i class="require">*</i> :</label>
                                <input class="form-control" id="str_LOGIN" name="str_LOGIN" placeholder="Nom d'utilisateur" min="4" type="text" required="">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_MOBILE" class=" control-label">Mobile <i class="require">*</i> :</label>
                                
                                <input class="form-control" id="str_MOBILE" name="str_MOBILE" placeholder="48708129" type="tel" required="">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_PASSWORD" class="control-label">Mot de passe :</label>
                                <input class="form-control" id="str_PASSWORD" name="str_PASSWORD" placeholder="Entrez le mot de passe" type="password" min="9" >
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_CPASSWORD" class=" control-label">Confirmer le mot de passe :</label>
                                <input class="form-control" id="str_CPASSWORD" name="str_CPASSWORD" placeholder="Confirmer le mot de passe" type="password" min="9" >
                            </div>
                            
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_NAME" class=" control-label">Nom <i class="require">*</i> :</label>
                                
                                <input class="form-control" id="str_NAME" name="str_NAME" placeholder="Nom" type="text" required="">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_LASTNAME" class=" control-label">Prénom <i class="require">*</i> :</label>
                                
                                <input class="form-control" id="str_LASTNAME" name="str_LASTNAME" placeholder="Prénom" type="text" required="">
                            </div>
                            
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_FIXE" class=" control-label">Fixe <i class="require">*</i> :</label>
                                
                                <input class="form-control" id="str_FIXE" name="str_FIXE" placeholder="22447071" type="tel" required="">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-xs-6 col-sm-6">
                                <label for="str_EMAIL" class=" control-label">E-mail <i class="require">*</i> :</label>
                                
                                <input class="form-control" id="str_EMAIL" name="str_EMAIL" placeholder="test@gmail.com" type="email" required="">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <label for="str_ILLUSTRATION" class=" control-label">Photo de profil :</label>
                                
                                <input class="form-control" id="str_ILLUSTRATION" name="str_ILLUSTRATION" placeholder="icon" type="file" accept="image/gif, image/jpeg, image/png">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <span>Les champs avec "<i class="require">*</i>" sont réquis </span>
                                <input type="hidden" name="editAccount" value="editAccount"/>
                                <input id="lg_CUSTOMER_ID" name="lg_CUSTOMER_ID" type="hidden">
                                <input id="lg_SECURITY_ID" name="lg_SECURITY_ID" type="hidden">
                                <button type="submit" class="btn btn-warning pull-right btn-lg" style="margin-left: 3px;">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
}
?>
