var url = "composant/com_receipt/controlerReceipt.php";
var datatable = "";
$(function () {
    getAllReceipt("");
    getCollection("");

    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_QUITTANCE_EDIT = $('#modal_edit_key #str_QUITTANCE_EDIT').val();
        var str_LETTRAGE_EDIT = $('#modal_edit_key #str_LETTRAGE_EDIT').val();
        var str_QUITTANCE_ID = $('#modal_edit_key #str_QUITTANCE_ID').val();
        if (str_QUITTANCE_EDIT == "" || str_LETTRAGE_EDIT =="" || str_QUITTANCE_ID == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editReceipt(str_QUITTANCE_EDIT, str_LETTRAGE_EDIT, str_QUITTANCE_ID);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_QUITTANCE = $('#add_key_form #str_QUITTANCE').val();
        var str_LETTRAGE = $('#add_key_form #str_LETTRAGE').val();

        if (str_QUITTANCE == "" || str_LETTRAGE == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addReceipt(str_QUITTANCE, str_LETTRAGE);
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


function getAllReceipt(str_RECEIPT_ID){
    var task = "getAllReceipt";
    
    $.get(url+"?task="+task+"&str_RECEIPT_ID="+str_RECEIPT_ID, function(json, textStatus){
        
        var obj = $.parseJSON(json);
        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_QUITTANCE_ID + '"></tr>');
                    var td_LIBELLE = $('<td class="column-data-table">' + results[i].str_LIBELLE + '</td>');
                    var td_NUMERO_LETTRAGE = $('<td class="column-data-table">' + results[i].str_NUMERO_LETTRAGE + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_RECEIPT_ID').val(id_key);
                        getKeyById(id_key);
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
                                deleteReceipt(id_key);
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
                    var td_rien = $('<td></td>');
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_LIBELLE);
                    tr.append(td_NUMERO_LETTRAGE);
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
function addReceipt(str_QUITTANCE, str_LETTRAGE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addReceipt=addReceipt&str_QUITTANCE=' + str_QUITTANCE + '&str_LETTRAGE='+str_LETTRAGE,
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
                getAllReceipt("");
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
function editReceipt(str_QUITTANCE_EDIT, str_LETTRAGE_EDIT, str_QUITTANCE_ID) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editReceipt=editReceipt&str_QUITTANCE_EDIT=' + str_QUITTANCE_EDIT + '&str_LETTRAGE_EDIT='+str_LETTRAGE_EDIT+'&str_QUITTANCE_ID='+str_QUITTANCE_ID,
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
                getAllReceipt("");
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
function deleteReceipt(str_RECEIPT_ID) {
    //alert(str_RECEIPT_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteReceipt&str_RECEIPT_ID=' + str_RECEIPT_ID,
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
                getAllReceipt("");
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
function getKeyById(str_RECEIPT_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllReceipt";
    $.get(url + "?task=" + task + "&str_RECEIPT_ID=" + str_RECEIPT_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#modal_edit_key #str_QUITTANCE_ID').val(results[i].str_QUITTANCE_ID);
                    $('#modal_edit_key #str_QUITTANCE_EDIT').val(results[i].str_LIBELLE);
                    $('#modal_edit_key #str_LETTRAGE_EDIT').val(results[i].str_ENCAISSEMENT_ID);
                });
            }
        }
    });
}
function getCollection(str_COLLECTION_ID){
    var task = "getAllCollection";
    $.get(url+"?task="+task+'&str_COLLECTION_ID='+str_COLLECTION_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_ENCAISSEMENT_ID + '">'+results[i].str_NUMERO_LETTRAGE+'</option>');
                    $('#modal_add_key #str_LETTRAGE').append(option);

                    var optionEdit = $('<option value="' + results[i].str_ENCAISSEMENT_ID + '">'+results[i].str_NUMERO_LETTRAGE+'</option>');
                    $('#modal_edit_key #str_LETTRAGE_EDIT').append(optionEdit);
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