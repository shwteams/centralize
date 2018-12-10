var url = "composant/com_production/controllerProduction.php";
var datatable = "";
$(function () {
    getAllProduction("");

    getContract('');
    getIntermediate('');

    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_PRODUCTION_ID = $('#modal_edit_key #str_PRODUCTION_ID').val();
        var str_CONTRACT_ID = $('#modal_edit_key #str_CONTRACT_EDIT_ID').val();
        var str_INTERMEDIATE_ID = $('#modal_edit_key #str_INTERMEDIATE_EDIT_ID').val();
        var dt_EFFET = $('#modal_edit_key #dt_EFFET_EDIT').val();
        var dt_ECHEANCE = $('#modal_edit_key #dt_ECHEANCE_EDIT').val();
        var int_PRIME_TTC = $('#modal_edit_key #int_PRIME_TTC_EDIT').val();
        var int_COMMISSION = $('#modal_edit_key #int_COMMISSION_EDIT').val();
        var int_PRIME_NET = $('#modal_edit_key #int_PRIME_NET_EDIT').val();
        var int_TAUX_COMMISSION = $('#modal_edit_key #int_TAUX_COMMISSION_EDIT').val();
        var int_AVENANT = $('#modal_edit_key #int_AVENANT_EDIT').val();
        var int_MAJO = $('#modal_edit_key #int_MAJO_EDIT').val();

        if (str_PRODUCTION_ID == "" || str_CONTRACT_ID == "" || str_INTERMEDIATE_ID == "" || dt_EFFET == "" || dt_ECHEANCE == "" || int_PRIME_TTC == "" || int_COMMISSION == "" || int_PRIME_NET == "" || int_TAUX_COMMISSION == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editProduction(str_PRODUCTION_ID, str_CONTRACT_ID, str_INTERMEDIATE_ID, dt_EFFET, dt_ECHEANCE, int_PRIME_TTC, int_COMMISSION, int_PRIME_NET, int_TAUX_COMMISSION, int_AVENANT, int_MAJO);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_CONTRACT_ID = $('#add_key_form #str_CONTRACT_ID').val();
        var str_INTERMEDIATE_ID = $('#add_key_form #str_INTERMEDIATE_ID').val();
        var dt_EFFET = $('#add_key_form #dt_EFFET').val();
        var dt_ECHEANCE = $('#add_key_form #dt_ECHEANCE').val();
        var int_PRIME_TTC = $('#add_key_form #int_PRIME_TTC').val();
        var int_COMMISSION = $('#add_key_form #int_COMMISSION').val();
        var int_PRIME_NET = $('#add_key_form #int_PRIME_NET').val();
        var int_TAUX_COMMISSION = $('#add_key_form #int_TAUX_COMMISSION').val();
        var int_AVENANT = $('#add_key_form #int_AVENANT').val();
        var int_MAJO = $('#add_key_form #int_MAJO').val();
        if (str_CONTRACT_ID == "" || str_INTERMEDIATE_ID == "" || dt_EFFET == "" || dt_ECHEANCE == "" || int_PRIME_TTC == "" || int_COMMISSION == "" || int_PRIME_NET == "" || int_TAUX_COMMISSION == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addProduction(str_CONTRACT_ID, str_INTERMEDIATE_ID, dt_EFFET, dt_ECHEANCE, int_PRIME_TTC, int_COMMISSION, int_PRIME_NET, int_TAUX_COMMISSION, int_AVENANT, int_MAJO);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
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


function getAllProduction(str_PRODUCTION_ID){
    var task = "getAllProduction";

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
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        $('#edit-key-form #str_PRODUCTION_ID').val(id_key);
                        getKeyById(id_key);
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
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
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
function addProduction(str_CONTRACT_ID, str_INTERMEDIATE_ID, dt_EFFET, dt_ECHEANCE, int_PRIME_TTC, int_COMMISSION, int_PRIME_NET, int_TAUX_COMMISSION, int_AVENANT, int_MAJO) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addProduction=addProduction&str_CONTRACT_ID=' + str_CONTRACT_ID+"&str_INTERMEDIATE_ID="+str_INTERMEDIATE_ID+"&dt_EFFET="+dt_EFFET+"&dt_ECHEANCE="+dt_ECHEANCE+"&int_PRIME_TTC="+int_PRIME_TTC+"&int_COMMISSION="+int_COMMISSION+"&int_PRIME_NET="+int_PRIME_NET+"&int_TAUX_COMMISSION="+int_TAUX_COMMISSION+"&int_AVENANT="+int_AVENANT+"&int_MAJO="+int_MAJO,
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
                getAllProduction("");
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
function editProduction(str_PRODUCTION_ID, str_CONTRACT_ID, str_INTERMEDIATE_ID, dt_EFFET, dt_ECHEANCE, int_PRIME_TTC, int_COMMISSION, int_PRIME_NET, int_TAUX_COMMISSION, int_AVENANT, int_MAJO) {

    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editProduction=editProduction&str_PRODUCTION_ID=' + str_PRODUCTION_ID+"&str_CONTRACT_ID="+str_CONTRACT_ID+"&str_INTERMEDIATE_ID="+str_INTERMEDIATE_ID+"&dt_EFFET="+dt_EFFET+"&dt_ECHEANCE="+dt_ECHEANCE+"&int_PRIME_TTC="+int_PRIME_TTC+"&int_COMMISSION="+int_COMMISSION+"&int_PRIME_NET="+int_PRIME_NET+"&int_TAUX_COMMISSION="+int_TAUX_COMMISSION+"&int_AVENANT="+int_AVENANT+"&int_MAJO="+int_MAJO,
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
                getAllProduction("");
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
function deleteProduction(str_PRODUCTION_ID) {
    //alert(str_PRODUCTION_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteProduction&str_PRODUCTION_ID=' + str_PRODUCTION_ID,
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
                getAllProduction("");
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
function getKeyById(str_PRODUCTION_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllProduction";
    $.get(url + "?task=" + task + "&str_PRODUCTION_ID=" + str_PRODUCTION_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    //alert(results[i].str_CONTRAT_ID)
                    $('#modal_edit_key #str_PRODUCTION_ID').val(results[i].str_PRODUCTION_ID);
                    $('#modal_edit_key #str_CONTRACT_EDIT_ID').val(results[i].str_CONTRAT_ID);
                    $('#modal_edit_key #str_INTERMEDIATE_EDIT_ID').val(results[i].str_INTERMEDIAIRE_ID);
                    $('#modal_edit_key #dt_EFFET_EDIT').val(results[i].dt_EFFET);
                    $('#modal_edit_key #dt_ECHEANCE_EDIT').val(results[i].dt_ECHEANCE);
                    $('#modal_edit_key #int_PRIME_TTC_EDIT').val(results[i].int_PRIME_TTC);
                    $('#modal_edit_key #int_COMMISSION_EDIT').val(results[i].int_COMMISSION);
                    $('#modal_edit_key #int_PRIME_NET_EDIT').val(results[i].int_PRIME_NET);
                    $('#modal_edit_key #int_TAUX_COMMISSION_EDIT').val(results[i].int_TAUX_COMMISSION);
                    $('#modal_edit_key #int_AVENANT_EDIT').val(results[i].int_AVENAN);
                    $('#modal_edit_key #int_MAJO_EDIT').val(results[i].int_MAJO);
                });
            }
        }
    });
}

function getContract(str_CONTRACT_ID){
    var task = "getAllContract";
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
                    $('#modal_add_key #str_CONTRACT_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</tr>');
                    $('#modal_edit_key #str_CONTRACT_EDIT_ID').append(optionEdit);
                });
            }
        }
    });
}
function getIntermediate(str_INTERMEDIATE_ID){
    var task = "getAllIntermediate";
    $.get(url+"?task="+task+'&str_INTERMEDIAIRE_ID='+str_INTERMEDIATE_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_INTERMEDIAIRE_ID + '">'+results[i].str_NOM+'</tr>');
                    $('#modal_add_key #str_INTERMEDIATE_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_INTERMEDIAIRE_ID + '">'+results[i].str_NOM+'</tr>');
                    $('#modal_edit_key #str_INTERMEDIATE_EDIT_ID').append(optionEdit);
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