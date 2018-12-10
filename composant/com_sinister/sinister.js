var url = "composant/com_sinister/controlerSinister.php";
var datatable = "";
$(function () {
    getAllSinister("");

    getContrat('');
    getEtat('');
    getAutreObersation('');
    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_SINISTER_ID = $('#modal_edit_key #str_SINISTER_EDIT_ID').val();
        var str_NUMERO_SINISTRE = $('#modal_edit_key #str_NUMERO_SINISTRE_EDIT').val();
        var str_CONTRAT_ID = $('#modal_edit_key #str_CONTRAT_EDIT_ID').val();
        var dt_SURVENANCE = $('#modal_edit_key #dt_SURVENANCE_EDIT').val();
        var dt_TRAITEMENT = $('#modal_edit_key #dt_TRAITEMENT_EDIT').val();
        var mt_EVAL = $('#modal_edit_key #mt_EVAL_EDIT').val();
        var mt_CUMULE_PAYE = $('#modal_edit_key #mt_CUMULE_PAYE_EDIT').val();
        var mt_PROVISION = $('#modal_edit_key #mt_PROVISION_EDIT').val();

        if (str_NUMERO_SINISTRE=="" || str_SINISTER_ID == "" || str_CONTRAT_ID == "" || dt_SURVENANCE == "" || dt_TRAITEMENT == "" || mt_EVAL == "" || mt_PROVISION == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else if(str_NUMERO_SINISTRE.length != 15)
        {
            swal({
                title: "Echec",
                text: "Le numéro de sinistre est incorrecte, merci de bien le renseigner",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else {
            editSinister(str_SINISTER_ID, str_NUMERO_SINISTRE, str_CONTRAT_ID, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_PROVISION);
        }

    });

    $('#modal_editing_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_SINISTER_ID = $('#modal_editing_key #str_SINISTER_EDITING_ID').val();
        //alert(str_SINISTER_ID)
        var str_CONTRAT_ID = $('#modal_editing_key #str_CONTRAT_EDITING_ID').val();
        var str_NUMERO_SINISTRE = $('#modal_editing_key #str_NUMERO_SINISTRE_EDITING').val();
        var dt_SURVENANCE = $('#modal_editing_key #dt_SURVENANCE_EDITING').val();
        var dt_TRAITEMENT = $('#modal_editing_key #dt_TRAITEMENT_EDITING').val();
        var mt_EVAL = $('#modal_editing_key #mt_EVAL_EDITING').val();
        var mt_CUMULE_PAYE = $('#modal_editing_key #mt_CUMULE_PAYE_EDITING').val();
        var mt_PROVISION = $('#modal_editing_key #mt_PROVISION_EDITING').val();

        var int_BONI = $('#modal_editing_key #int_BONI').val();
        var int_MALI= $('#modal_editing_key #int_MALI').val();
        var str_OBSERVATION = $('#modal_editing_key #str_OBSERVATION').val();
        var str_AUTRE_OBSERVATION_ID = $('#modal_editing_key #str_AUTRE_OBSERVATION_ID').val();
        var str_ETAT_ID = $('#modal_editing_key #str_ETAT_ID').val();
        var int_BONI_POTENTIEL = $('#modal_editing_key #int_BONI_POTENTIEL').val();

       // alert(int_BONI_POTENTIEL+" "+int_MALI+" "+str_OBSERVATION+" "+str_AUTRE_OBSERVATION_ID+" "+str_ETAT_ID+" "+int_BONI_POTENTIEL)
        if (str_NUMERO_SINISTRE == "" || int_BONI == "" || int_MALI == "" || str_AUTRE_OBSERVATION_ID == "" || str_ETAT_ID == "" || str_SINISTER_ID == "" || str_CONTRAT_ID == "" || dt_SURVENANCE == "" || dt_TRAITEMENT == "" || mt_EVAL == "" || mt_CUMULE_PAYE == ""  || mt_PROVISION == "" || int_BONI_POTENTIEL =="") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }else if(str_NUMERO_SINISTRE.length != 15)
        {
            swal({
                title: "Echec",
                text: "Le numéro de sinistre est incorrecte, merci de bien le renseigner",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else {
            editingSinister(str_SINISTER_ID, str_NUMERO_SINISTRE, str_CONTRAT_ID, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_CUMULE_PAYE, mt_PROVISION, int_BONI, int_MALI, str_OBSERVATION,  str_AUTRE_OBSERVATION_ID, str_ETAT_ID, int_BONI_POTENTIEL);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_CONTRAT_ID = $('#add_key_form #str_CONTRAT_ID').val();
        var str_NUMERO_SINISTRE = $('#add_key_form #str_NUMERO_SINISTRE').val();
        var dt_SURVENANCE = $('#add_key_form #dt_SURVENANCE').val();
        var dt_TRAITEMENT = $('#add_key_form #dt_TRAITEMENT').val();
        var mt_EVAL = $('#add_key_form #mt_EVAL').val();
        var mt_PROVISION = $('#add_key_form #mt_PROVISION').val();
        var mt_PROVISION_FIN = $('#add_key_form #mt_PROVISION_FIN').val();

        if (str_NUMERO_SINISTRE == "" || str_CONTRAT_ID == "" || dt_SURVENANCE == "" || dt_TRAITEMENT == "" || mt_EVAL == "" || mt_PROVISION == ""  || mt_PROVISION_FIN == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else if(str_NUMERO_SINISTRE.length != 15)
        {
            swal({
                title: "Echec",
                text: "Le numéro de sinistre est incorrecte, merci de bien le renseigner",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else {
            //console.log("add table")
            addSinister(str_CONTRAT_ID, str_NUMERO_SINISTRE, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_PROVISION, mt_PROVISION_FIN);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        /*$('#modal_edit_key #str_NAME').val("");
         $('#modal_edit_key #int_NUMBER_PLACE').val("");*/
        $('.modal[id="modal_add_key"]').modal('show');
        /*$('#modal_add_key select').select2({
            language: "fr"
        });*/
    });
    $('.date-picker').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                maxDate:jQuery('.date_timepicker_end').val()?jQuery('.date_timepicker_end').val():false
            })
        },
        timepicker:false
    });
    /*$('.date-picker').datetimepicker({
        onGenerate:function( ct ){
            $(this).find('.xdsoft_date')
                .toggleClass('xdsoft_disabled');
        },
        minDate:'-1970/01/02',
        maxDate:'+1970/01/02',
        timepicker:false,
        format:'Y/m/d'
    });**/
    $("#int_MALI").on('keyup', function(e){
        $('#int_BONI').val(0)
        //alert('ok')
    });
    $("#int_BONI").on('keyup', function(e){
        $('#int_MALI').val(0)
    });
});

function getEtat(str_ETAT_ID){
    var task = "getAllEtats";
    $.get(url+"?task="+task+'&str_ETAT_ID='+str_ETAT_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var optionEdit = $('<option value="' + results[i].str_ETAT_ID + '">'+results[i].str_LIBELLE+'</tr>');
                    $('#modal_editing_key #str_ETAT_ID').append(optionEdit);
                });
            }
        }
    });
}

function getAutreObersation(str_AUTRE_OBSERVATION_ID){
    var task = "getAllAutreObservation";
    $.get(url+"?task="+task+'&str_AUTRE_OBSERVATION_ID='+str_AUTRE_OBSERVATION_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var optionEdit = $('<option value="' + results[i].str_AUTRE_OBSERVATION_ID + '">'+results[i].str_LIBELLE+'</tr>');
                    $('#modal_editing_key #str_AUTRE_OBSERVATION_ID').append(optionEdit);
                });
            }
        }
    });
}

function getContrat(str_CONTRACT_ID){
    var task = "getAllContrat";
    $.get(url+"?task="+task+'&str_CONTRACT_ID='+str_CONTRACT_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</tr>');
                    $('#modal_add_key #str_CONTRAT_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</tr>');
                    $('#modal_edit_key #str_CONTRAT_EDIT_ID').append(optionEdit);

                    var optionEditing = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</tr>');
                    $('#modal_editing_key #str_CONTRAT_EDITING_ID').append(optionEditing);
                });
            }
        }
    });
}
function getAllSinister(str_SINISTER_ID){
    var task = "getAllSinister";
    
    $.get(url+"?task="+task+"&str_SINISTER_ID="+str_SINISTER_ID, function(json, textStatus){
        
        var obj = $.parseJSON(json);
        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_SINISTRE_ID + '"></tr>');
                    var td_NUMERO_SINISTRE = $('<td class="column-data-table">' + results[i].str_NUMERO_SINISTRE + '</td>');
                    var td_NUMERO_POLICE = $('<td class="column-data-table">' + results[i].str_NUMERO_POLICE + '</td>');
                    var td_SURVENANCE = $('<td class="column-data-table">' + results[i].dt_SURVENANCE + '</td>');
                    var td_TRAITEMENT = $('<td class="column-data-table">' + results[i].dt_TRAITEMENT + '</td>');
                    var td_EVAL = $('<td class="column-data-table">' + results[i].mt_EVAL + '</td>');
                    //var td_CUMULE_PAYE = $('<td class="column-data-table">' + results[i].mt_CUMULE_PAYE + '</td>');
                    var td_PROVISION = $('<td class="column-data-table">' + results[i].mt_PROVISION + '</td>');
                    var td_BONI = $('<td class="column-data-table">' + results[i].mt_BONI + '</td>');
                    var td_MALI = $('<td class="column-data-table">' + results[i].mt_MALI + '</td>');
                    var td_OBSERVATION = $('<td class="column-data-table">' + results[i].str_OBSERVATION + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_SINISTER_ID').val(id_key);
                        getKeyById(id_key);
                    });

                    var btn_editing = $('<span class=" btn-action-custom btn-action-edit" id="modal_editing_key" data-toggle="modal"  title="Traiter"><i class="fa fa-plus"></i> | </span> ').click(function () {
                        $('.modal[id="modal_editing_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        //$('#str_SINISTER_EDITING_ID').val(id_key);
                        $('#modal_editing_key #str_SINISTER_EDIT_ID').val(id_key);
                        getKeyByIdEditing(id_key);
                    });
                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> <i class="fa fa-trash"></i></span>').click(function () {
                        var id_key = $(this).parent().parent().attr('id');
                        
                        haveSession();
                        swal({
                            title: 'Demande de Confirmation',
                            text: "Etes-vous sûr de vouloir supprimer cette donnée ?'",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f44336',
                            cancelButtonColor: '#f48928',
                            confirmButtonText: 'Oui',
                            cancelButtonText: 'Non',
                            confirmButtonClass: 'btn btn-success',
                            cancelButtonClass: 'btn btn-danger',
                            buttonsStyling: false,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                deleteSinister(id_key);
                            } else {
                                swal(
                                    'Annulation',
                                    'Opération annulé',
                                    'error'
                                );
                            }
                        })
                        getKeyById(id_key);
                    });
                    var td_rien = $('<td class="column-data-table"></td>');
                    var td_action = $('<td class="column-data-table" align="center"></td>');
                    td_action.append(btn_editing);
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_NUMERO_SINISTRE);
                    tr.append(td_NUMERO_POLICE);
                    tr.append(td_SURVENANCE);
                    tr.append(td_TRAITEMENT);
                    tr.append(td_EVAL);
                    //tr.append(td_CUMULE_PAYE);
                    tr.append(td_PROVISION)
                    tr.append(td_action);
                    tr.append(td_rien);
                    $("#examples tbody").append(tr);
                });
            }
            
                
            if ($.fn.dataTable.isDataTable('#example')) {
                table = $('#example').DataTable();
            }
            else {
                table = $('#example').DataTable({
                    paging: false
                });
            }
            datatable = $('#examples').DataTable({
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements",
                    "zeroRecords": "Aucune ligne trouvée",
                    "info": "Affichage des enregistrements _START_ &agrave; _END_ sur _TOTAL_ enregistrements",
                    "infoEmpty": "Aucun enregistrement trouvé",
                    "infoFiltered": "(filtr&eacute; de _MAX_ enregistrements au total)",
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "search": "Recherche",
                    "zeroRecords":    "Aucun enregistrement &agrave; afficher",
                            "paginate": {
                                "first": "Premier",
                                "last": "Dernier",
                                "next": "Suivant",
                                "previous": "Précédent"
                            }
                },
                aria: {
                    sortAscending: ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }, responsive: true, retrieve: true
            });
        }

    });
}
function addSinister(str_CONTRAT_ID, str_NUMERO_SINISTRE, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_PROVISION, mt_PROVISION_FIN) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addSinister=addSinister&str_CONTRAT_ID=' + str_CONTRAT_ID+'&str_NUMERO_SINISTRE='+str_NUMERO_SINISTRE+'&dt_SURVENANCE='+dt_SURVENANCE+'&dt_TRAITEMENT='+dt_TRAITEMENT+'&mt_EVAL='+mt_EVAL+'&mt_PROVISION='+mt_PROVISION+'&mt_PROVISION_FIN='+mt_PROVISION_FIN,
        dataType: 'text',
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                swal({
                    title: "Opération réussie!",
                    text: obj[0].results,
                    type: "success",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
                if ($.fn.DataTable.isDataTable('#examples')) {
                    if ($.fn.DataTable.isDataTable('#examples')) {
                        datatable.destroy();
                    }
                }
                getAllSinister("");
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
            }
        }
    });
}
function editingSinister(str_SINISTER_ID, str_NUMERO_SINISTRE, str_CONTRAT_ID, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_CUMULE_PAYE, mt_PROVISION, int_BONI, int_MALI, str_OBSERVATION,  str_AUTRE_OBSERVATION_ID, str_ETAT_ID, int_BONI_POTENTIEL) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editingSinister=editingSinister&str_SINISTER_ID='+str_SINISTER_ID+'&str_NUMERO_SINISTRE='+str_NUMERO_SINISTRE+'&str_CONTRAT_ID='+str_CONTRAT_ID+'&mt_EVAL='+mt_EVAL+'&mt_CUMULE_PAYE='+mt_CUMULE_PAYE+'&mt_PROVISION='+mt_PROVISION+'&int_BONI='+int_BONI+'&int_MALI='+int_MALI+'&str_OBSERVATION='+str_OBSERVATION+'&str_AUTRE_OBSERVATION_ID='+str_AUTRE_OBSERVATION_ID+'&str_ETAT_ID='+str_ETAT_ID+'&int_BONI_POTENTIEL='+int_BONI_POTENTIEL,
        dataType: 'text',
        success: function (response) {
            //alert(json);return;
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                swal({
                    title: "Opération réussie!",
                    text: obj[0].results,
                    type: "success",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
                if ($.fn.DataTable.isDataTable('#examples')) {
                    datatable.destroy();
                }
                getAllSinister("");
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
            }
        }
    });
}
function editSinister(str_SINISTER_ID, str_NUMERO_SINISTRE, str_CONTRAT_ID, dt_SURVENANCE, dt_TRAITEMENT, mt_EVAL, mt_PROVISION) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editSinister=editSinister&str_SINISTER_ID='+str_SINISTER_ID+'&str_NUMERO_SINISTRE='+str_NUMERO_SINISTRE+'&str_CONTRAT_ID='+str_CONTRAT_ID+'&dt_SURVENANCE='+dt_SURVENANCE+'&dt_TRAITEMENT='+dt_TRAITEMENT+'&mt_EVAL='+mt_EVAL+'&mt_PROVISION='+mt_PROVISION,
        dataType: 'text',
        success: function (response) {
            //alert(json);return;
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                swal({
                    title: "Opération réussie!",
                    text: obj[0].results,
                    type: "success",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
                if ($.fn.DataTable.isDataTable('#examples')) {
                    datatable.destroy();
                }
                getAllSinister("");
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
            }
        }
    });
}
function deleteSinister(str_SINISTER_ID) {
    //alert(str_SINISTER_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteSinister&str_SINISTER_ID=' + str_SINISTER_ID,
        dataType: 'text',
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                swal({
                    title: "Opération réussie!",
                    text: obj[0].results,
                    type: "success",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
                if ($.fn.DataTable.isDataTable('#examples')) {
                    datatable.destroy();
                }
                getAllSinister("");
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $('#contenue-application .modal').modal('hide');
            }
        }
    });
}
function getKeyById(str_SINISTER_ID)
{
    //alert(str_SINISTER_ID);
    var task = "getAllSinister";
    $.get(url + "?task=" + task + "&str_SINISTER_ID=" + str_SINISTER_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    //alert(results[i].str_SINISTER_ID)
                    $('#modal_edit_key #str_SINISTER_EDIT_ID').val(results[i].str_SINISTRE_ID);
                    $('#modal_edit_key #str_CONTRAT_EDIT_ID').val(results[i].str_CONTRAT_ID);
                    $('#modal_edit_key #dt_SURVENANCE_EDIT').val(results[i].dt_SURVENANCE);
                    $('#modal_edit_key #str_NUMERO_SINISTRE_EDIT').val(results[i].str_NUMERO_SINISTRE);
                    $('#modal_edit_key #dt_TRAITEMENT_EDIT').val(results[i].dt_TRAITEMENT);
                    $('#modal_edit_key #mt_EVAL_EDIT').val(results[i].mt_EVAL);
                    $('#modal_edit_key #mt_CUMULE_PAYE_EDIT').val(results[i].mt_CUMULE_PAYE);
                    $('#modal_edit_key #mt_PROVISION_EDIT').val(results[i].mt_PROVISION);
                    $('#modal_edit_key #mt_PROVISION_FIN_EDIT').val(results[i].mt_PROVISION_FIN);
                });
            }
        }
    });
}

function getKeyByIdEditing(str_SINISTER_ID)
{
    //alert(str_SINISTER_ID);
    var task = "getAllSinister";
    $.get(url + "?task=" + task + "&str_SINISTER_ID=" + str_SINISTER_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    //alert(results[i].str_SINISTER_ID)
                    $('#modal_editing_key #str_SINISTER_EDITING_ID').val(results[i].str_SINISTRE_ID);
                    $('#modal_editing_key #str_NUMERO_SINISTRE_EDITING').val(results[i].str_NUMERO_SINISTRE);
                    $('#modal_editing_key #str_CONTRAT_EDITING_ID').val(results[i].str_CONTRAT_ID);
                    $('#modal_editing_key #dt_SURVENANCE_EDITING').val(results[i].dt_SURVENANCE);
                    $('#modal_editing_key #dt_TRAITEMENT_EDITING').val(results[i].dt_TRAITEMENT);
                    $('#modal_editing_key #mt_EVAL_EDITING').val(results[i].mt_EVAL);
                    $('#modal_editing_key #mt_CUMULE_PAYE_EDITING').val(results[i].mt_CUMULE_PAYE);
                    $('#modal_editing_key #mt_PROVISION_EDITING').val(results[i].mt_PROVISION);
                    $('#modal_editing_key #mt_PROVISION_FIN_EDITING').val(results[i].mt_PROVISION_FIN);
                    $('#modal_editing_key #int_BONI').val(results[i].mt_BONI);
                    $('#modal_editing_key #int_MALI').val(results[i].mt_MALI);
                    $('#modal_editing_key #int_BONI_POTENTIEL').val(results[i].mt_BONI_POTENTIEL);
                    $('#modal_editing_key #str_OBSERVATION').val(results[i].str_OBSERVATION);
                    $('#modal_editing_key #str_AUTRE_OBSERVATION_ID').val(results[i].str_AUTRE_OBSERVATION_ID);
                    $('#modal_editing_key #str_ETAT_ID').val(results[i].str_ETAT_ID);
                });
            }
        }
    });
}
function isInteger(string) {
    var regx = /^\d+$/;
    if (!regx.test(string)) {
        return false
    } else {
        return true;
    }
}