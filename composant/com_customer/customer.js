var url = "composant/com_customer/controllerCustomer.php";
var datatable = "";
$(function () {
    getAllCustomer("");

    getPartner('');
    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();haveSession();
        var str_PARTNER_EDIT_ID = $('#modal_edit_key #str_PARTNER_EDIT_ID').val();
        var str_LIBELLE_EDIT = $('#modal_edit_key #str_LIBELLE_EDIT').val();
        var str_CUSTOMER_ID = $('#modal_edit_key #str_CUSTOMER_ID').val();
        //alert(str_CUSTOMER_ID);
        if (str_LIBELLE_EDIT == "" || str_PARTNER_EDIT_ID == "" || str_CUSTOMER_ID == "") {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous le champ",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            editeCustomer(str_LIBELLE_EDIT, str_PARTNER_EDIT_ID, str_CUSTOMER_ID);
        }

    });

    $('#add_key_form').submit(function (e) {
        e.preventDefault();haveSession();
        var str_NOM = $('#add_key_form #str_NOM').val();
        var str_PARTNER_ID = $('#add_key_form #str_PARTNER_ID').val();
        if (str_NOM == "" || str_PARTNER_ID == "" ) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            //console.log("add table")
            addCustomer(str_NOM,str_PARTNER_ID);
        }
    });

    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
    });

    $(".searchCustomer").keyup(function () {
        var query = $(this).val();
        var outPut = '';
        if(query != ''){
            $.ajax({
                url: url, // La ressource ciblée
                type: 'GET', // Le type de la requête HTTP.
                data: 'task=searchCustomer&str_CUSTOMER_NAME=' + query,
                dataType: 'text',
                success: function (response) {
                    var obj = $.parseJSON(response);
                    if (obj[0].code_statut == "1")
                    {
                        var results = obj[0].results;

                        if (obj[0].results.length > 0)
                        {
                            $(".customerList").fadeIn();
                            outPut = $('<ul class="list-unstyled"></ul>');
                            $.each(results, function (i, value)//
                            {
                                datas = $('<li>'+ results[i].str_NOM +'</li>');
                                outPut.append(datas);
                            });
                        }
                        $(".customerList").html(outPut);
                    } else {
                        $(".customerList").html("Aucun enregistrement trouvé pour le moment");
                    }
                }
            });
        }
    })
    $(".customerList").on('click', 'li', function () {
        $('#str_NOM').val($(this).text());
        console.log($(this).closest('ul').attr('id'));
        $('.customerList').fadeOut();
    });
});


function getAllCustomer(str_CUSTOMER_ID){
    var task = "getAllCustomer";

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
                    var tr = $('<tr class="line-data-table" id="' + results[i].str_CLIENT_ID + '"></tr>');
                    var td_LIBELLE = $('<td class="column-data-table">' + results[i].str_NOM + '</td>');
                    var td_TYPE = $('<td class="column-data-table">' + results[i].str_LIBELLE + '</td>');
                    var btn_edit = $('<span class=" btn-action-custom btn-action-edit" id="modal_edit_key" data-toggle="modal"  title="Modifier"><i class="fa fa-edit"></i> | </span> ').click(function () {
                        $('.modal[id="modal_edit_key"]').modal('show');
                        var id_key = $(this).parent().parent().attr('id');
                        //alert(id_key);return;
                        $('#edit-key-form #str_CUSTOMER_ID').val(id_key);
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
                                    deleteCustomer(id_key);
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
                    tr.append(td_TYPE);
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
function addCustomer(str_NOM, str_PARTNER_ID) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'addCustomer=addCustomer&str_NOM=' + str_NOM+"&str_PARTNER_ID="+str_PARTNER_ID,
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
                getAllCustomer("");
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
function editeCustomer(str_LIBELLE_EDIT, str_PARTNER_EDIT_ID, str_CUSTOMER_ID) {
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editeCustomer=editeCustomer&str_LIBELLE_EDIT=' + str_LIBELLE_EDIT + '&str_PARTNER_EDIT_ID='+str_PARTNER_EDIT_ID + '&str_CUSTOMER_ID='+str_CUSTOMER_ID,
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
                getAllCustomer("");
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
function deleteCustomer(str_CUSTOMER_ID) {
    //alert(str_CUSTOMER_ID);
    $.ajax({
        url: url, // La ressource ciblée
        type: 'GET', // Le type de la requête HTTP.
        data: 'task=deleteCustomer&str_CUSTOMER_ID=' + str_CUSTOMER_ID,
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
                getAllCustomer("");
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
function getKeyById(str_CUSTOMER_ID)
{
    //alert(lg_OFFRE_ID);
    var task = "getAllCustomer";
    $.get(url + "?task=" + task + "&str_CUSTOMER_ID=" + str_CUSTOMER_ID, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#modal_edit_key #str_PARTNER_EDIT_ID').val(results[i].str_PARTENAIRE_ID);
                    $('#modal_edit_key #str_CUSTOMER_ID').val(results[i].str_CLIENT_ID);
                    $('#modal_edit_key #str_LIBELLE_EDIT').val(results[i].str_NOM);
                });
            }
        }
    });
}

function getPartner(str_PARTNER_ID){
    var task = "getAllPartner";
    $.get(url+"?task="+task+'&str_PARTNER_ID='+str_PARTNER_ID, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].str_PARTENAIRE_ID + '">'+results[i].str_LIBELLE+'</tr>');
                    $('#modal_add_key #str_PARTNER_ID').append(option);

                    var optionEdit = $('<option value="' + results[i].str_PARTENAIRE_ID + '">'+results[i].str_LIBELLE+'</tr>');
                    $('#modal_edit_key #str_PARTNER_EDIT_ID').append(optionEdit);
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