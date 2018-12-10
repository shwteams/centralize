var url = "composant/com_not_collected_production/controllerNotCollectedProduction.php";
var datatable = "";
$(function () {
    getAllProdNotCollected("");
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
    $('#extract_key_form').submit(function (e) {
        e.preventDefault();
        var str_FILE_NAME = $('#extract_key_form #str_FILE_NAME').val();
        extractXSLData(str_FILE_NAME);
    });
    $('.btn[id="modal_extract_key"]').click(function () {
        $("#download").addClass('hidden');
        $('.modal[id="modal_extract_key"]').modal('show');
    });
    $("#download").click(function () {
        $('.modal[id="modal_extract_key"]').modal('hide');
    })
});
function extractXSLData(str_FILE_NAME) {
    /*
    var str_GESTIONNAIRE = $("#form_filter #str_GESTIONNAIRE").val();
    var str_DATE_DEBUT = $("#form_filter #str_DATE_DEBUT").val();
    var str_DATE_FIN = $("#form_filter #str_DATE_FIN").val();
    var str_ETAT_ID = $("#form_filter #str_ETAT").val(); */
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: "extractXSLData=extractXSLData&str_FILE_NAME="+str_FILE_NAME,
        dataType: 'text',
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1") {
                $("#download").removeClass('hidden');
                $("#download").attr('href', obj[0].file);
                swal({
                    title: "Opération réussie!",
                    text: "Operation reussi",
                    type: "success",
                    confirmButtonText: "Ok"
                });
            } else {
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                $("#download").addClass('hidden');
                $('#contenue-application .modal').modal('hide');
            }
        }
    });
}
function getAllProdNotCollected(str_PRODUCTION_ID){
    var task = "getAllProdNotCollected";

    $.get(url+"?task="+task+"&str_PRODUCTION_ID="+str_PRODUCTION_ID, function(json, textStatus){

        var obj = $.parseJSON(json);
        $("#examples tbody").empty();

        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_PRODUCTION_ID + '"></tr>');
                    var td_POLICE_NUMBER = $('<td class="column-data-table">' + results[i].str_NUMERO_POLICE + '</td>');
                    var td_EFFET = $('<td class="column-data-table">' + results[i].dt_EFFET + '</td>');
                    var td_ECHEANCE = $('<td class="column-data-table">' + results[i].dt_ECHEANCE + '</td>');
                    var td_PRIME_TTC = $('<td class="column-data-table">' + results[i].int_PRIME_TTC + '</td>');
                    var td_COMMISSION = $('<td class="column-data-table">' + results[i].int_COMMISSION + '</td>');
                    var td_PRIME_NET = $('<td class="column-data-table">' + results[i].int_PRIME_NET + '</td>');
                    var td_TAUX_COMMISSION = $('<td class="column-data-table">' + results[i].int_TAUX_COMMISSION + '</td>');
                    var td_AVENAN = $('<td class="column-data-table">' + results[i].int_AVENAN + '</td>');
                    var td_MAJO = $('<td class="column-data-table">' + results[i].int_MAJO + '</td>');
                    var td_INTERMEDIAIRE = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var btn_add = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Ajouter encaissement"><i class="fa fa-plus"></i>  </span> ').click(function () {
                        $('.modal[id="modal_add_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        $('#add_key_form #str_NUMERO_POLICE').val(results[i].str_NUMERO_POLICE);
                        $('#add_key_form #int_PRIME_TTC').val(results[i].int_PRIME_TTC);
                        $('#add_key_form #dt_EFFET').val(results[i].dt_EFFET);
                        $('#add_key_form #dt_ECHEANCE').val(results[i].dt_ECHEANCE);
                        //getKeyById(id_key);
                    });
                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> <i class="fa fa-trash"></i></span>').click(function () {
                        var id_key = $(this).parent().parent().attr('id');
						haveSession();
                        swal({
                                title: 'Demande de Confirmation',
                                text: "Etes-vous sûr de vouloir supprimer cette donnée ?",
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
                                    deleteProduction(id_key);
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
                    td_action.append(btn_add);
                    //td_action.append(btn_delete);
                    tr.append(td_POLICE_NUMBER);
                    tr.append(td_EFFET);
                    tr.append(td_ECHEANCE);
                    tr.append(td_PRIME_TTC);
                    tr.append(td_COMMISSION);
                    tr.append(td_PRIME_NET);
                    tr.append(td_TAUX_COMMISSION);
                    tr.append(td_AVENAN);
                    tr.append(td_MAJO);
                    tr.append(td_INTERMEDIAIRE);
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
                getAllProdNotCollected("");
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
function isInteger(string) {
    var regx = /^\d+$/;
    if (!regx.test(string)) {
        return false
    } else {
        return true;
    }
}