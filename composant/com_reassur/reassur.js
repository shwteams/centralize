var url = "composant/com_reassur/controlerReassur.php";
var datatable = "";
$(function () {
    getAllReassur("");
    getAllContractWhoHaveProduction("");
    getReassureur("");

    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        haveSession();
        var str_NUMERO_POLICE = $('#modal_edit_key #str_NUMERO_POLICE_EDIT').val();
        var dt_ENTREE = $('#modal_edit_key #dt_ENTREE_EDIT').val();
        var dt_SORTIE = $('#modal_edit_key #dt_SORTIE_EDIT').val();
        var str_IMMATRICULATION = $('#modal_edit_key #str_IMMATRICULATION_EDIT').val();
        var str_CODE_REGULATION = $('#modal_edit_key #str_CODE_REGULATION_EDIT').val();
        var int_NBRPLACE = $('#modal_edit_key #int_NBRPLACE_EDIT').val();
        var int_VALEUR = $('#modal_edit_key #int_VALEUR_EDIT').val();
        var int_POID = $('#modal_edit_key #int_POID_EDIT').val();
        var str_CONTRAT_ENTREPRISE_ID = $('#modal_edit_key #str_CONTRAT_ENTREPRISE_ID').val();

        if (str_CONTRAT_ENTREPRISE_ID == "" || str_NUMERO_POLICE == "" || dt_ENTREE == "" || str_IMMATRICULATION == "" || str_CODE_REGULATION == "" || int_NBRPLACE == "" || int_VALEUR == "" || int_POID == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editCompanyContract(str_NUMERO_POLICE, dt_ENTREE, dt_SORTIE, str_IMMATRICULATION, str_CODE_REGULATION, int_NBRPLACE, int_VALEUR, int_POID, str_CONTRAT_ENTREPRISE_ID);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        haveSession();
        let str_NUMERO_POLICE = $('#add_key_form #str_NUMERO_POLICE').val();
        let int_AVENANT = $('#add_key_form #int_AVENANT').val();
        let str_REASSUREUR_ID = $('#add_key_form #str_REASSUREUR_ID').val();
        let int_PAR_SUNU = parseInt($('#add_key_form #int_PAR_SUNU').val());
        let int_PAR_REASSUREUR = parseInt($('#add_key_form #int_PAR_REASSUREUR').val());


        if (str_NUMERO_POLICE == "" || int_AVENANT == "" || str_REASSUREUR_ID == "" || int_PAR_SUNU == "" || int_PAR_REASSUREUR == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else if((int_PAR_SUNU+int_PAR_REASSUREUR) < 0 || (int_PAR_SUNU+int_PAR_REASSUREUR)>100)
        {
            swal({
                title: "Echec",
                text: "La somme des pars doit être comprise  entre 0% et 100%.",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        }
        else {
            addReassur(str_NUMERO_POLICE, int_AVENANT, str_REASSUREUR_ID, int_PAR_SUNU, int_PAR_REASSUREUR);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
        /*$('#modal_add_key select').select2({
            language: "fr"
        });*/
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
    $(".getAvenant").on("change", function(){
        let str_CONTRAT_ID = $(this).val();
        if(str_CONTRAT_ID != "")
        {
            $.get(url+"?task=getAvenant&str_CONTRAT_ID="+str_CONTRAT_ID, function(response){
                let data = JSON.parse(response);
                addAvenantInDropDownListe(data)
            });
        }
        else
        {
            addAvenantInDropDownListe("")
        }
    });
});

function addAvenantInDropDownListe(data)
{
    let l = "";
    if(data != "")
    {
        results = data[0].results;
        $.each(results, function (i)
        {
            l+=`
            <option value="${results[i].str_PRODUCTION_ID}">${results[i].int_AVENAN}</option>
            `
        });
    }
    $("#int_AVENANT").html(l);
}


function getAllReassur(str_REASSUR_ID){
    var task = "getAllReassur";
    
    $.get(url+"?task="+task+"&str_REASSUR_ID="+str_REASSUR_ID, function(json, textStatus){
        //window.location.href = "composant/com_security/controlersecurity.php?task=disconnect";
        var obj = $.parseJSON(json);

        $("#examples tbody").empty();
        
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;
            
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_REASSUR_ID + '"></tr>');
                    var td_NUMERO_POLICE = $('<td class="column-data-table">' + results[i].str_NUMERO_POLICE + '</td>');
                    var td_REASSUREUR = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var td_par_SUNU = $('<td class="column-data-table">' + results[i].par_SUNU + '</td>');
                    var td_par_REASSUREUR = $('<td class="column-data-table">' + results[i].par_REASSUREUR + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_REASSUR_ID').val(id_key);
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
                                deleteReassur(id_key);
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
                    //td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_NUMERO_POLICE);
                    tr.append(td_REASSUREUR);
                    tr.append(td_par_SUNU);
                    tr.append(td_par_REASSUREUR);
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
function addReassur(str_NUMERO_POLICE, int_AVENANT, str_REASSUREUR_ID, int_PAR_SUNU, int_PAR_REASSUREUR) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addReassur=addReassur&str_NUMERO_POLICE=' + str_NUMERO_POLICE + '&int_AVENANT='+int_AVENANT+'&str_REASSUREUR_ID='+str_REASSUREUR_ID+'&int_PAR_SUNU='+int_PAR_SUNU+'&int_PAR_REASSUREUR='+int_PAR_REASSUREUR,
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
                getAllReassur("");
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
function editCompanyContract(str_NUMERO_POLICE, dt_ENTREE, dt_SORTIE, str_IMMATRICULATION, str_CODE_REGULATION, int_NBRPLACE, int_VALEUR, int_POID, str_CONTRAT_ENTREPRISE_ID) {
    //alert(str_CONTRAT_ENTREPRISE_ID)
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editCompanyContract=editCompanyContract&str_NUMERO_POLICE=' + str_NUMERO_POLICE + '&dt_ENTREE='+dt_ENTREE+'&dt_SORTIE='+dt_SORTIE+"&str_IMMATRICULATION="+str_IMMATRICULATION+"&str_CODE_REGULATION="+str_CODE_REGULATION+"&int_NBRPLACE="+int_NBRPLACE+"&int_VALEUR="+int_VALEUR+"&int_POID="+int_POID+"&str_CONTRAT_ENTREPRISE_ID="+str_CONTRAT_ENTREPRISE_ID,
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
                getAllReassur("");
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
function deleteReassur(str_REASSUR_ID) {
    //alert(str_REASSUR_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteReassur&str_REASSUR_ID=' + str_REASSUR_ID,
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
                getAllReassur("");
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
function getKeyById(str_REASSUR_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllReassur";
    $.get(url + "?task=" + task + "&str_REASSUR_ID=" + str_REASSUR_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#modal_edit_key #str_CONTRAT_ENTREPRISE_ID').val(results[i].str_CONTRATENTREPRISE_ID);
                    $('#modal_edit_key #str_NUMERO_POLICE_EDIT').val(results[i].str_CONTRAT_ID);
                    $('#modal_edit_key #dt_ENTREE_EDIT').val(results[i].dt_ENTRE);
                    $('#modal_edit_key #dt_SORTIE').val(results[i].dt_SORTIE);
                    $('#modal_edit_key #str_IMMATRICULATION_EDIT').val(results[i].str_IMMATRICULATION);
                    $('#modal_edit_key #str_CODE_REGULATION_EDIT').val(results[i].str_CODE_REGULATION);
                    $('#modal_edit_key #int_NBRPLACE_EDIT').val(results[i].int_NBRPLACE);
                    $('#modal_edit_key #int_VALEUR_EDIT').val(results[i].int_VALEUR_A_NEUF);
                    $('#modal_edit_key #int_POID_EDIT').val(results[i].int_POID);
                });
            }
        }
    });
}
function getAllContractWhoHaveProduction(str_CONTRACT_ID){
    var task = "getAllContractWhoHaveProduction";
    $.get(url+"?task="+task+'&str_CONTRACT_ID='+str_CONTRACT_ID, function(json, textStatus){
        let obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</option>');
                    $('#modal_add_key #str_NUMERO_POLICE').append(option);

                    var optionEdit = $('<option value="' + results[i].str_CONTRAT_ID + '">'+results[i].str_NUMERO_POLICE+'</option>');
                    $('#modal_edit_key #str_NUMERO_POLICE_EDIT').append(optionEdit);
                });
            }
        }
    });
}
function getReassureur(str_REINSURER_ID)
{
    let task ="?task=getReassureur&str_REINSURER_ID="+str_REINSURER_ID;
    //alert(url)
    $.get(url+`${task}`, function (responseJson) {
        let obj = $.parseJSON(responseJson);
        response = obj[0].results;
        l = "";
        if(response.length > 0)
        {
            $.each(response, function(i){
                l += `
                    <option value="${response[i].str_REASSUREUR_ID}">${response[i].str_NOM}</option>
                `;
            })
            $("#str_REASSUREUR_ID").html(l);
        }
    })
}
function isInteger(string) {
    var regx = /^\d+$/;
    if (!regx.test(string)) {
        return false
    } else {
        return true;
    }
}