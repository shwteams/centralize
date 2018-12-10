var url = "composant/com_contract/controllerContract.php";
var datatable = "";
$(function () {
    $(".target").hide("slow").fadeOut();
    getAllContract("");
    getPlugged('');
    getCustomer('');

    $('#str_PRODUIT_ADD').fadeOut();
    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();

        var str_CONTRACT_EDIT_ID = $('#modal_edit_key #str_CONTRACT_EDIT_ID').val();
        var str_PLUGGED_ID = $('#modal_edit_key #str_PLUGGED_EDIT_ID').val();
        var str_PRODUIT_ID = $('#modal_edit_key #str_PRODUIT_EDIT_ID').val();
        var str_ETAT = $('#modal_edit_key #str_ETAT_EDIT').val();
        var str_MOTIF = $('#modal_edit_key #str_MOTIF_EDIT').val();
        var str_POLICENUMBER = $('#modal_edit_key #str_POLICENUMBER_EDIT').val();
        var str_CUSTOMER_ID = $('#modal_edit_key #str_CUSTOMER_EDIT_ID').val();
        var int_NUNBERCAR = $('#modal_edit_key #int_NUNBERCAR_EDIT').val();
        if (str_CUSTOMER_ID == "" || str_PLUGGED_ID == "" || str_PRODUIT_ID == "" || str_ETAT == "" || str_POLICENUMBER == "" || str_CONTRACT_EDIT_ID == "")  {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editeContract(str_CONTRACT_EDIT_ID, str_CUSTOMER_ID, str_PLUGGED_ID, str_PRODUIT_ID, str_ETAT, str_POLICENUMBER, str_MOTIF, int_NUNBERCAR);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_PLUGGED_ID = $('#add_key_form #str_PLUGGED_ID').val();
        var str_PRODUIT_ID = $('#add_key_form #str_PRODUIT_ID').val();
        var str_ETAT = $('#add_key_form #str_ETAT').val();
        var str_MOTIF = $('#add_key_form #str_MOTIF').val();
        var str_POLICENUMBER = $('#add_key_form #str_POLICENUMBER').val();
        var str_CUSTOMER_ID = $('#add_key_form #str_CUSTOMER_ID').val();
        var int_NUNBERCAR = $('#add_key_form #int_NUNBERCAR').val();
        if (str_CUSTOMER_ID == "" || str_PLUGGED_ID == "" || str_PRODUIT_ID == "" || str_ETAT == "" || str_POLICENUMBER == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            addContract(str_CUSTOMER_ID, str_PLUGGED_ID, str_PRODUIT_ID, str_ETAT, str_POLICENUMBER, str_MOTIF, int_NUNBERCAR);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
    });
    $('#str_PRODUIT_ID').change(function () {
        filter = $("#str_PRODUIT_ID option:selected").text();
        viewFieldProduct(filter);
    })

    $("#str_PLUGGED_ID").change(function () {
        var str_PLUGGED_ID = $(this).val();
        if(str_PLUGGED_ID != ''){
            getProduct(str_PLUGGED_ID)
        }
        else {
            $("#str_PRODUIT_ADD").fadeOut();
        }
    });
    $("#str_PLUGGED_EDIT_ID").change(function () {
        var str_PLUGGED_ID = $(this).val();
        if(str_PLUGGED_ID != ''){
            getProductEdit(str_PLUGGED_ID)
        }
        else {
            $("#str_PRODUIT_ADD").fadeOut();
        }
    });

});

function viewFieldProduct(filter) {
    var bool = filter.search("Auto");
    if(bool != 0)
    {
        $(".target").hide("slow").fadeOut();
    }
    else {
        $(".target").show(1300);
    }
}
function getProduct(str_PLUGGED_ID){
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=getAllProductByPlugged&str_PLUGGED_ID=' + str_PLUGGED_ID,
        dataType: 'text',
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                var results = obj[0].results;
                $('#modal_add_key #str_PRODUIT_ID').html('<option value=""></option>');
                if (obj[0].results.length > 0)
                {
                    $("#str_PRODUIT_ADD").fadeIn();
                    $.each(results, function (i, value)//
                    {
                        var option = $('<option value="' + results[i].str_PRODUIT_ID + '">'+results[i].str_LIBELLE+'</option>');
                        var optionEdit = $('<option value="' + results[i].str_PRODUIT_ID + '">'+results[i].str_LIBELLE+'</option>');
                        $('#modal_add_key #str_PRODUIT_ID').append(option);
                        $('#modal_edit_key #str_PRODUIT_EDIT_ID').append(optionEdit);
                        //filter = results[i].str_LIBELLE;
                        //var optionEdit = $('<option value="' + results[i].str_BRANCHE_ID + '">'+results[i].str_LIBELLE+'</tr>');
                        //$('#modal_edit_key #str_PLUGGED_EDIT_ID').append(optionEdit);
                    });

                }
                else {
                    $("#str_PRODUIT_ADD").fadeOut();
                }
            } else {
                $("#str_PRODUIT_ADD").fadeOut();
            }
        }
    });
}
function getProductEdit(str_PLUGGED_ID ){
    $('#modal_edit_key #str_PRODUIT_EDIT_ID').html('<option value=""></option>');
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=getAllProductByPlugged&str_PLUGGED_ID=' + str_PLUGGED_ID,
        dataType: 'text',
        success: function (response) {
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                var results = obj[0].results;

                if (obj[0].results.length > 0)
                {
                    $("#str_PRODUIT_ADD").fadeIn();
                    $.each(results, function (i, value)//
                    {
                        var optionEdit = $('<option value="' + results[i].str_PRODUIT_ID + '">'+results[i].str_LIBELLE+'</option>');
                        $('#modal_edit_key #str_PRODUIT_EDIT_ID').append(optionEdit);
                    });
                }
                else {
                    $("#str_PRODUIT_ADD").fadeOut();
                }
            } else {
                $("#str_PRODUIT_ADD").fadeOut();
            }
        }
    });
}
function getAllContract(str_CUSTOMER_ID){
    var task = "getAllContract";

    $.get(url+"?task="+task+"&str_CUSTOMER_ID="+str_CUSTOMER_ID, function(json, textStatus){

        var obj = $.parseJSON(json);
        $("#examples tbody").empty();

        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_CONTRAT_ID + '"></tr>');
                    var td_NUMERO_POLICE = $('<td class="column-data-table">' + results[i].str_NUMERO_POLICE + '</td>');
                    var td_ETAT = $('<td class="column-data-table">' + (results[i].str_ETAT==1?'Actif':'Inactif') + '</td>');
                    var td_MOTIF = $('<td class="column-data-table">' + results[i].str_MOTIF + '</td>');
                    var td_NOMBRE_VEHICULE = $('<td class="column-data-table">' + (results[i].int_NOMBRE_VEHICULE==null?'':results[i].int_NOMBRE_VEHICULE) + '</td>');
                    var td_LIBELLE = $('<td class="column-data-table">' + results[i].str_LIBELLE + '</td>');
                    var td_LIBELLE_BRANCHE = $('<td class="column-data-table">' + results[i].str_LIBELLE_BRANCHE + '</td>');
                    var td_NOM = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_CONTRACT_EDIT_ID').val(id_key);
                        getKeyById(id_key);
                    });

                    var btn_delete = $('<span class="btn-action-custom btn-action-delete" title="Supprimer"> <i class="fa fa-trash"></i></span>').click(function () {
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);
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
                                    deleteContract(id_key);
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
                    var td_rien = $('<td class="column-data-table"></td>');
                    td_action.append(btn_edit);
                    td_action.append(btn_delete);
                    tr.append(td_NUMERO_POLICE);
                    tr.append(td_ETAT);
                    tr.append(td_MOTIF);
                    tr.append(td_NOMBRE_VEHICULE);
                    tr.append(td_LIBELLE);
                    tr.append(td_LIBELLE_BRANCHE);
                    tr.append(td_NOM);
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
function addContract(str_CUSTOMER_ID, str_PLUGGED_ID, str_PRODUIT_ID, str_ETAT, str_POLICENUMBER, str_MOTIF, int_NUNBERCAR) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addContract=addContract&str_PLUGGED_ID=' + str_PLUGGED_ID +"&str_PRODUIT_ID="+str_PRODUIT_ID +"&str_ETAT="+str_ETAT+"&str_POLICENUMBER="+str_POLICENUMBER+"&str_MOTIF="+str_MOTIF+'&str_CUSTOMER_ID='+str_CUSTOMER_ID+'&int_NUNBERCAR='+int_NUNBERCAR,
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
                getAllContract("");
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
function editeContract(str_CONTRACT_EDIT_ID, str_CUSTOMER_ID, str_PLUGGED_ID, str_PRODUIT_ID, str_ETAT, str_POLICENUMBER, str_MOTIF, int_NUNBERCAR) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editeContract=editeContract&str_CUSTOMER_ID=' + str_CUSTOMER_ID + '&str_PLUGGED_ID='+str_PLUGGED_ID + '&str_PRODUIT_ID='+str_PRODUIT_ID +'&str_ETAT='+str_ETAT+'&str_POLICENUMBER='+str_POLICENUMBER+'&str_MOTIF='+str_MOTIF+'&int_NUNBERCAR='+int_NUNBERCAR+'&str_CONTRACT_EDIT_ID='+str_CONTRACT_EDIT_ID,
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
                getAllContract("");
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
function deleteContract(str_CONTRACT_ID) {
    //alert(str_CUSTOMER_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteContract&str_CONTRACT_ID=' + str_CONTRACT_ID,
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
                getAllContract("");
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
function getKeyById(str_CONTRACT_ID)
{
    //alert(str_CUSTOMER_ID);
    var task = "getAllContract";
    $.get(url + "?task=" + task + "&str_CONTRACT_ID=" + str_CONTRACT_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    getProductEdit('');
                    //console.log(results[i].str_LIBELLE);
                    $('#modal_edit_key #str_CONTRACT_EDIT_ID').val(results[i].str_CONTRAT_ID);
                    $('#modal_edit_key #str_PLUGGED_EDIT_ID').val(results[i].str_BRANCHE_ID);
                    $('#modal_edit_key #str_CUSTOMER_EDIT_ID').val(results[i].str_CLIENT_ID);

                    $('#modal_edit_key #str_ETAT_EDIT').val((results[i].str_ETAT==1?'on':'off'));
                    $(".target").show();
                    filter = results[i].str_LIBELLE;
                    viewFieldProduct(filter);
                    $('#modal_edit_key #str_PRODUIT_EDIT_ID').val(filter);

                    $('#modal_edit_key #str_MOTIF_EDIT').val(results[i].str_MOTIF);
                    $('#modal_edit_key #int_NUNBERCAR_EDIT').val(results[i].int_NOMBRE_VEHICULE);
                    $('#modal_edit_key #str_POLICENUMBER_EDIT').val(results[i].str_NUMERO_POLICE);
                });
            }
        }
    });
}

function getPlugged(str_PLUGGED_ID){
    var task = "getAllPlugged";
    $.get(url+"?task="+task+'&str_PLUGGED_ID='+str_PLUGGED_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_BRANCHE_ID + '">'+results[i].str_LIBELLE+'</option>');
                    $('#modal_add_key #str_PLUGGED_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_BRANCHE_ID + '">'+results[i].str_LIBELLE+'</option>');
                    $('#modal_edit_key #str_PLUGGED_EDIT_ID').append(optionEdit);
                });
            }
        }
    });
}

function getCustomer(str_CUSTOMER_ID){
    var task = "getAllCustomer";
    $.get(url+"?task="+task+'&str_CUSTOMER_ID='+str_CUSTOMER_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_CLIENT_ID + '">'+results[i].str_NOM+'</option>');
                    $('#modal_add_key #str_CUSTOMER_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_CLIENT_ID + '">'+results[i].str_NOM+'</option>');
                    $('#modal_edit_key #str_CUSTOMER_EDIT_ID').append(optionEdit);
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