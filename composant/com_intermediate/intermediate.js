var url = "composant/com_intermediate/controlerIntermediate.php";
var datatable = "";
$(function () {
    getAllIntermediate("");

    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_INTERMEDIATE_ID = $('#modal_edit_key #str_INTERMEDIATE_ID').val();
        var str_NOM = $('#modal_edit_key #str_LIBELLE_EDIT').val();
        var str_CODE = $('#modal_edit_key #str_CODE_EDIT').val();
        if (str_NOM == "" || str_CODE == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editeIntermediate(str_INTERMEDIATE_ID, str_NOM, str_CODE);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_INTERMEDIATE_ID = $('#add_key_form #str_INTERMEDIATE_ID').val();
        var str_NOM = $('#add_key_form #str_NOM').val();
        var str_CODE = $('#add_key_form #str_CODE').val();
        if (str_INTERMEDIATE_ID == "" || str_NOM == "" || str_CODE == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addIntermediate(str_INTERMEDIATE_ID, str_NOM, str_CODE);
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


function getAllIntermediate(str_INTERMEDIATE_ID){
    var task = "getAllIntermediate";
    
    $.get(url+"?task="+task+"&str_INTERMEDIATE_ID="+str_INTERMEDIATE_ID, function(json, textStatus){
        
        var obj = $.parseJSON(json);
        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_INTERMEDIAIRE_ID+ '"></tr>');
                    var td_LIBELLE = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var td_CODE = $('<td class="column-data-table">' + results[i].str_CODE + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_INTERMEDIATE_ID').val(id_key);
                        getKeyById(id_key);
                    });
                    
                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> <i class="fa fa-trash"></i></span>').click(function () {
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);
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
                                deleteIntermediate(id_key);
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
                    var td_action = $('<td class="column-data-table" align="center"></td>');
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_LIBELLE);
                    tr.append(td_CODE);
                    tr.append(td_action);
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
function addIntermediate(str_INTERMEDIATE_ID, str_NOM, str_CODE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addIntermediate=addIntermediate&str_NOM=' + str_NOM +'&str_CODE='+str_CODE,
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
                getAllIntermediate("");
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
function editeIntermediate(str_INTERMEDIATE_ID, str_NOM, str_CODE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editeIntermediate=editeIntermediate&str_NOM_EDIT=' + str_NOM + '&str_CODE_EDIT='+str_CODE+'&str_INTERMEDIATE_ID='+str_INTERMEDIATE_ID,
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
                getAllIntermediate("");
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
function deleteIntermediate(str_INTERMEDIATE_ID) {
    //alert(str_INTERMEDIATE_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteIntermediate&str_INTERMEDIATE_ID=' + str_INTERMEDIATE_ID,
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
                getAllIntermediate("");
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
function getKeyById(str_INTERMEDIATE_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllIntermediate";
    $.get(url + "?task=" + task + "&str_INTERMEDIATE_ID=" + str_INTERMEDIATE_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    //alert(results[i].str_INTERMEDIAIRE_ID)
                    $('#modal_edit_key #str_INTERMEDIATE_ID').val(results[i].str_INTERMEDIAIRE_ID);
                    $('#modal_edit_key #str_LIBELLE_EDIT').val(results[i].str_NOM);
                    $('#modal_edit_key #str_CODE_EDIT').val(results[i].str_CODE);
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