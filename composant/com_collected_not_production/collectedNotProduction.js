var url = "composant/com_collected_not_production/controllerCollectedNotProduction.php";
var datatable = "";
$(function () {
    getAllCollectedNotProd("");

    getContract('');
    getIntermediate('');


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
function getAllCollectedNotProd(str_COLLECTION_ID){
    var task = "getAllCollectedNotProd";

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
                    var btn_add = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Ajouter production"><i class="fa fa-plus"></i> </span> ').click(function () {

                        $('.modal[id="modal_add_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        $('#add_key_form #str_NUMERO_POLICE').val(results[i].pk_NUMERO_POLICE);
                        $('#add_key_form #int_PRIME_TTC').val(results[i].int_PRIME_TTC);
                        $('#add_key_form #dt_EFFET').val(results[i].dt_EFFET);
                        $('#add_key_form #dt_ECHEANCE').val(results[i].dt_ECHEANCE);
                    });


                    var td_action = $('<td class="column-data-table" align="center"></td>');
                    var td_rien = $('<td class="column-data-table" align="center"></td>');
                    td_action.append(btn_add);
                    //td_action.append(btn_delete);
                    tr.append(td_CREATED);
                    tr.append(td_NUMERO_LETTRAGE);
                    tr.append(td_NUMERO_POLICE);
                    tr.append(td_PRIME_TTC);
                    tr.append(td_EFFET);
                    tr.append(td_ECHEANCE);
                    //tr.append(td_action);
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

function getKeyById(str_NUMERO_POLICE)
{
    var task = "getAllCollectedPaie";
    $.get(url + "?task=" + task + "&str_NUMERO_POLICE=" + str_NUMERO_POLICE, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#modal_edit_key #info_POLICE').html('<b>'+results[i].str_NOM+' ('+results[i].str_NUMERO_POLICE+')</b>');
                    $('#modal_edit_key #td_EFFET').html(results[i].dt_EFFET);
                    $('#modal_edit_key #td_ECHEANCE').html(results[i].dt_ECHEANCE);
                    $('#modal_edit_key #PRIME_TOTAL').html('<b>'+Intl.NumberFormat("de-DE", { currency: "CFA"}).format(results[i].int_PRIME_TTC)+'</b>');
                    //alert(results[i].str_POLICE)
                });
            }
            var obj = $.parseJSON(json);
            $("#examples-encaissement tbody").empty();

            if (obj[0].code_statutEncaissement == "1")
            {
                //alert("ok")
                var resultsEncaissement = obj[0].resultsEncaissement;

                if (obj[0].resultsEncaissement.length > 0)
                {
                    $.each(resultsEncaissement, function (i, value)//
                    {
                        //console.log(resultsEncaissement[i].str_NOFACT);
                        var tr = $('<tr class="line-data-table" id="' + resultsEncaissement[i].str_ENCAISSEMENT_ID + '"></tr>');
                        var td_CREATED = $('<td class="column-data-table"><b>' + resultsEncaissement[i].dt_CREATED + '</b></td>');
                        var td_LETTRAGE = $('<td class="column-data-table"><b>' + resultsEncaissement[i].str_NUMERO_LETTRAGE + '</b></td>');
                        var td_EFFET = $('<td class="column-data-table">' + resultsEncaissement[i].dt_EFFET + '</td>');
                        var td_ECHEANCE = $('<td class="column-data-table">' + resultsEncaissement[i].dt_ECHEANCE + '</td>');
                        var td_PRIMETTC = $('<td class="column-data-table"><b>' + Intl.NumberFormat("de-DE", { currency: "CFA"}).format(resultsEncaissement[i].int_PRIME_TTC) + '</b></td>');

                        tr.append(td_CREATED);
                        tr.append(td_LETTRAGE);
                        tr.append(td_EFFET);
                        tr.append(td_ECHEANCE);
                        tr.append(td_PRIMETTC);
                        $("#examples-encaissement tbody").append(tr);
                    });
                }

                if ($.fn.dataTable.isDataTable('#examples-encaissement')) {
                    table = $('#examples-encaissement').DataTable();
                }
                else {
                    table = $('#examples-encaissement').DataTable({
                        paging: false
                    });
                }
                datatable = $('#examples-encaissement').DataTable({
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
                getAllCollectedNotProd("");
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
                getAllCollectedNotProd("");
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