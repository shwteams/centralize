var url = "composant/com_user/controlerUser.php";
var datatable = "";
$(function () {
    getAllUser("");
    $('#str_PASSWORD_CONF').focusout( function () {
        var str_PASSWORD = $('#str_PASSWORD').val();
        var str_PASSWORD_CONF = $('#str_PASSWORD_CONF').val();
        if(str_PASSWORD != str_PASSWORD_CONF){
            swal({
                title: "Echec",
                text: "Les mots de passes ne sont passe identique.",
                type: "error",
                confirmButtonText: "Ok"
            });
            $('#str_PASSWORD_CONF').val('');
            $('#saved').addClass('hidden');
            return false;
        }else {
            $('#saved').removeClass('hidden');
        }
    });
    $('#str_PASSWORD_CONF_EDIT').focusout( function () {
        var str_PASSWORD_EDIT = $('#str_PASSWORD_EDIT').val();
        var str_PASSWORD_CONF_EDIT = $('#str_PASSWORD_CONF_EDIT').val();
        if(str_PASSWORD_EDIT != str_PASSWORD_CONF_EDIT){
            swal({
                title: "Echec",
                text: "Les mots de passes ne sont passe identique.",
                type: "error",
                confirmButtonText: "Ok"
            });
            $('#str_PASSWORD_CONF_EDIT').val('');
            $('#saved_edit').addClass('hidden');
            return false;
        }else {
            $('#saved_edit').removeClass('hidden');
        }
    });
    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();
        var str_SECURITY_ID = $('#modal_edit_key #str_SECURITY_ID').val();
        var str_NAME = $('#modal_edit_key #str_NAME_EDIT').val();
        var str_LASTNAME = $('#modal_edit_key #str_LASTNAME_EDIT').val();
        var str_EMAIL = $('#modal_edit_key #str_EMAIL_EDIT').val();
        var str_LOGIN = $('#modal_edit_key #str_LOGIN_EDIT').val();
        var str_PASSWORD = $('#modal_edit_key #str_PASSWORD_EDIT').val();
        var str_PASSWORD_CONF = $('#modal_edit_key #str_PASSWORD_CONF_EDIT').val();
        var str_PRIVILEGE = $('#modal_edit_key #str_PRIVILEGE_EDIT').val();
        if (str_SECURITY_ID == "" || str_NAME == "" || str_LASTNAME == "" || str_EMAIL == "" || str_LOGIN == "" || str_PASSWORD == "" || str_PASSWORD_CONF =="" || str_PRIVILEGE == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editSecurity(str_SECURITY_ID, str_NAME, str_LASTNAME, str_EMAIL, str_LOGIN, str_PASSWORD, str_PASSWORD_CONF, str_PRIVILEGE);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();
        var str_SECURITY_ID = $('#add_key_form #str_SECURITY_ID').val();
        var str_NAME = $('#add_key_form #str_NAME').val();
        var str_LASTNAME = $('#add_key_form #str_LASTNAME').val();
        var str_EMAIL = $('#add_key_form #str_EMAIL').val();
        var str_LOGIN = $('#add_key_form #str_LOGIN').val();
        var str_PASSWORD = $('#add_key_form #str_PASSWORD').val();
        var str_PASSWORD_CONF = $('#add_key_form #str_PASSWORD_CONF').val();
        var str_SECURITY_UP_ID = $('#add_key_form #str_SECURITY_UP_ID').val();
        var str_PRIVILEGE = $('#add_key_form #str_PRIVILEGE').val();
        if (str_SECURITY_ID == "" || str_NAME == "" || str_LASTNAME == "" || str_EMAIL == "" || str_LOGIN == "" || str_PASSWORD == "" || str_PASSWORD_CONF =="" || str_SECURITY_UP_ID == "" || str_PRIVILEGE == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addSecurity(str_SECURITY_ID, str_NAME, str_LASTNAME, str_EMAIL, str_LOGIN, str_PASSWORD, str_PASSWORD_CONF, str_PRIVILEGE);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        /*$('#modal_edit_key #str_NAME').val("");
         $('#modal_edit_key #int_NUMBER_PLACE').val("");*/
        $('.modal[id="modal_add_key"]').modal('show');
        $('#modal_add_key select').select2({
            language: "fr"
        });
    });
});


function getAllUser(str_SECURITY_ID){
    var task = "getAllUser";
    
    $.get(url+"?task="+task+"&str_SECURITY_ID="+str_SECURITY_ID, function(json, textStatus){
        
        var obj = $.parseJSON(json);
        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_SECURITY_ID + '"></tr>');
                    var td_LOGIN = $('<td class="column-data-table">' + results[i].str_LOGIN + '</td>');
                    var td_NAME = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var td_LASTNAME = $('<td class="column-data-table">' + results[i].str_PRENOM + '</td>');
                    var td_EMAIL = $('<td class="column-data-table">' + results[i].str_EMAIL + '</td>');
                    var td_PRIVILEGE = $('<td class="column-data-table">' + results[i].str_PRIVILEGE + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_SECURITY_ID').val(id_key);
                        getKeyById(id_key);
                    });
                    
                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> <i class="fa fa-trash"></i></span>').click(function () {
                        var id_key = $(this).parent().parent().attr('id');
                        
                        //$('#edit-key-form #lg_OFFRE_ID').val(id_key);
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
                                deleteSecurity(id_key);
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
                    var td_rien= $('<td class="column-data-table"></td>');
                    var td_action = $('<td class="column-data-table" align="center"></td>');
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_LOGIN);
                    tr.append(td_NAME);
                    tr.append(td_LASTNAME);
                    tr.append(td_EMAIL);
                    tr.append(td_PRIVILEGE);
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
function addSecurity(str_SECURITY_ID, str_NAME, str_LASTNAME, str_EMAIL, str_LOGIN, str_PASSWORD, str_PASSWORD_CONF, str_PRIVILEGE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addSecurity=addSecurity&str_NAME=' + str_NAME + "&str_LASTNAME=" + str_LASTNAME + '&str_EMAIL=' +str_EMAIL+'&str_LOGIN='+str_LOGIN+'&str_PASSWORD='+str_PASSWORD+'&str_PASSWORD_CONF='+str_PASSWORD_CONF+'&str_PRIVILEGE='+str_PRIVILEGE,
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
                getAllUser("");
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
function editSecurity(str_SECURITY_ID, str_NAME, str_LASTNAME, str_EMAIL, str_LOGIN, str_PASSWORD, str_PASSWORD_CONF, str_PRIVILEGE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editeSecurity=editeSecurity&str_NAME_EDIT=' + str_NAME + "&str_LASTNAME_EDIT=" + str_LASTNAME + '&str_EMAIL_EDIT=' +str_EMAIL+'&str_LOGIN_EDIT='+str_LOGIN+'&str_PASSWORD_EDIT='+str_PASSWORD+'&str_PASSWORD_CONF_EDIT='+str_PASSWORD_CONF+'&str_PRIVILEGE_EDIT='+str_PRIVILEGE+'&str_SECURITY_ID='+str_SECURITY_ID,
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
                getAllUser("");
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
function deleteSecurity(str_SECURITY_ID) {
    //alert(str_SECURITY_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteSecurity&str_SECURITY_ID=' + str_SECURITY_ID,
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
                getAllUser("");
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
function getKeyById(str_SECURITY_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllUser";
    $.get(url + "?task=" + task + "&str_SECURITY_ID=" + str_SECURITY_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#modal_edit_key #str_SECURITY_ID').val(results[i].str_SECURITY_ID);
                    $('#modal_edit_key #str_LOGIN_EDIT').val(results[i].str_LOGIN);
                    $('#modal_edit_key #str_NAME_EDIT').val(results[i].str_NOM);
                    $('#modal_edit_key #str_LASTNAME_EDIT').val(results[i].str_PRENOM);
                    $('#modal_edit_key #str_EMAIL_EDIT').val(results[i].str_EMAIL);
                    $('#modal_edit_key #str_PRIVILEGE_EDIT').val(results[i].str_PRIVILEGE);
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