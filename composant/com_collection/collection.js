var url = "composant/com_collection/controlerCollection.php";
var datatable = "";
$(function () {
    getAllCollection("");

    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();

        var str_COLLECTION_ID = $('#modal_edit_key #str_COLLECTION_ID').val();
        var str_LETTRAGE = $('#modal_edit_key #str_LETTRAGE_EDIT').val();
        var str_NUMERO_POLICE = $('#modal_edit_key #str_NUMERO_POLICE_EDIT').val();
        var int_PRIME_TTC = $('#modal_edit_key #int_PRIME_TTC_EDIT').val();
        var dt_EFFET = $('#modal_edit_key #dt_EFFET_EDIT').val();
        var dt_ECHEANCE = $('#modal_edit_key #dt_ECHEANCE_EDIT').val();
        
        if (str_COLLECTION_ID == "" || str_LETTRAGE == "" || str_NUMERO_POLICE == "" || int_PRIME_TTC == "" || dt_EFFET == "" || dt_ECHEANCE == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editCollection(str_COLLECTION_ID, str_LETTRAGE, str_NUMERO_POLICE, int_PRIME_TTC, dt_EFFET, dt_ECHEANCE);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_LETTRAGE = $('#add_key_form #str_LETTRAGE').val();
        var str_NUMERO_POLICE = $('#add_key_form #str_NUMERO_POLICE').val();
        var int_PRIME_TTC = $('#add_key_form #int_PRIME_TTC').val();
        var dt_EFFET = $('#add_key_form #dt_EFFET').val();
        var dt_ECHEANCE = $('#add_key_form #dt_ECHEANCE').val();

        if (str_LETTRAGE == "" || str_NUMERO_POLICE == "" || int_PRIME_TTC == "" || dt_EFFET == "" || dt_ECHEANCE == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addCollection(str_LETTRAGE, str_NUMERO_POLICE, int_PRIME_TTC, dt_EFFET, dt_ECHEANCE);
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
    $('.date_timepicker_start').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                maxDate:jQuery('.date_timepicker_end').val()?jQuery('.date_timepicker_end').val():false
            })
        },
        timepicker:false
    });
    $('.date_timepicker_end').datetimepicker({
        format:'Y-m-d',
        onShow:function( ct ){
            this.setOptions({
                minDate:jQuery('.date_timepicker_start').val()?jQuery('.date_timepicker_start').val():false
            })
        },
        timepicker:false
    });
});


function getAllCollection(str_COLLECTION_ID){
    var task = "getAllCollection";
    
    $.get(url+"?task="+task+"&str_COLLECTION_ID="+str_COLLECTION_ID, function(json, textStatus){
        
        var obj = $.parseJSON(json);
        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_ENCAISSEMENT_ID + '"></tr>');
                    var td_CREATED = $('<td class="column-data-table">' + results[i].	dt_CREATED + '</td>');
                    var td_NUMERO_LETTRAGE = $('<td class="column-data-table">' + results[i].str_NUMERO_LETTRAGE + '</td>');
                    var td_NUMERO_POLICE = $('<td class="column-data-table">' + results[i].pk_NUMERO_POLICE + '</td>');
                    var td_PRIME_TTC = $('<td class="column-data-table">' + results[i].int_PRIME_TTC + '</td>');
                    var td_EFFET = $('<td class="column-data-table">' + results[i].dt_EFFET + '</td>');
                    var td_ECHEANCE = $('<td class="column-data-table">' + results[i].dt_ECHEANCE + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        $('#modal_edit_key #str_COLLECTION_ID').val(id_key);
                        getKeyById(id_key);
                    });
                    
                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> | <i class="fa fa-trash"></i></span>').click(function () {
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
                                deleteCollection(id_key);
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
                    var td_rien = $('<td class="column-data-table" align="center"></td>');
                    td_action.append(btn_edit);
                    //td_action.append(btn_delete);
                    tr.append(td_CREATED);
                    tr.append(td_NUMERO_LETTRAGE);
                    tr.append(td_NUMERO_POLICE);
                    tr.append(td_PRIME_TTC);
                    tr.append(td_EFFET);
                    tr.append(td_ECHEANCE);
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
function addCollection(str_LETTRAGE, str_NUMERO_POLICE, int_PRIME_TTC, dt_EFFET, dt_ECHEANCE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addCollection=addCollection&str_LETTRAGE=' + str_LETTRAGE+"&str_NUMERO_POLICE="+str_NUMERO_POLICE+"&int_PRIME_TTC="+int_PRIME_TTC+"&dt_EFFET="+dt_EFFET+"&dt_ECHEANCE="+dt_ECHEANCE,
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
                getAllCollection("");
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
function editCollection(str_COLLECTION_ID, str_LETTRAGE, str_NUMERO_POLICE, int_PRIME_TTC, dt_EFFET, dt_ECHEANCE) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editCollection=editCollection&str_COLLECTION_ID=' + str_COLLECTION_ID + '&str_LETTRAGE='+str_LETTRAGE+"&str_NUMERO_POLICE="+str_NUMERO_POLICE+"&int_PRIME_TTC="+int_PRIME_TTC+"&dt_EFFET="+dt_EFFET+"&dt_ECHEANCE="+dt_ECHEANCE,
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
                getAllCollection("");
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
function deleteCollection(str_COLLECTION_ID) {
    //alert(str_COLLECTION_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteCollection&str_COLLECTION_ID=' + str_COLLECTION_ID,
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
                getAllCollection("");
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
function getKeyById(str_COLLECTION_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllCollection";
    $.get(url + "?task=" + task + "&str_COLLECTION_ID=" + str_COLLECTION_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    //$('#modal_edit_key #str_COLLECTION_ID').val(results[i].str_ENCAISSMENT_ID);
                    $('#modal_edit_key #str_LETTRAGE_EDIT').val(results[i].str_NUMERO_LETTRAGE);
                    $('#modal_edit_key #str_NUMERO_POLICE_EDIT').val(results[i].pk_NUMERO_POLICE);
                    $('#modal_edit_key #int_PRIME_TTC_EDIT').val(results[i].int_PRIME_TTC);
                    $('#modal_edit_key #dt_EFFET_EDIT').val(results[i].dt_EFFET);
                    $('#modal_edit_key #dt_ECHEANCE_EDIT').val(results[i].dt_ECHEANCE);
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