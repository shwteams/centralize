var url = "composant/com_IAintegration/controllerIAintegration.php";
var datatable = "";
$(function () {
    getDatabaseTables();
    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
    });
    $('#str_TABLES').on('change', function (e) {
        e.preventDefault();
        let str_TABLES = $("#str_TABLES").val();
        //console.log(str_TABLES);
        describeTable(str_TABLES);
        getAlphabetiqueWord();
    });
});
function describeTable(str_TABLES)
{
    $('#putSelect').empty();
    var task = "describeTable";
    $.get(url+"?task="+task+"&str_TABLES="+str_TABLES, function(json, textStatus){
        var results = $.parseJSON(json);
        $.each(results, function (i, value)//
        {
            let container = $("<div class='form-group'></div>");
            let datas = results[i].field;
            let label = $("<label class='col-sm-4 control-label' for='"+datas+"'>"+datas+"</label>");
            let div = $("<div class='col-sm-8'><select class='form-control ma_liste' name='"+datas+"' required><option value=''></option></select></div>");

            container.append(label);
            container.append(div);
            $('#putSelect').append(container);
        });
    });
}

function getDatabaseTables(){
    var task = "getDatabaseTables";
    $.get(url+"?task="+task, function(json, textStatus){
        var results = $.parseJSON(json);
        $.each(results, function (i, value)//
        {
            var option = $('<option value="' + results[i].tables + '">'+results[i].tables+'</tr>');
            $('#str_TABLES').append(option);
        });
    });
}
function getAlphabetiqueWord(){
    var task = "getAlphabetiqueWord";
    $.get(url+"?task="+task, function(json, textStatus){
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {
            var results = obj[0].results;

            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)//
                {
                    var option = $('<option value="' + results[i].numRow + '">'+results[i].str_WORD+'</tr>');
                    $('.ma_liste').append(option);
                });
            }
        }
    });
}
function addRevue() {
    var form = $('#add_key_form').get(0);
    var formData = new FormData(form);
    $('#saved').addClass('hidden');
    $.ajax({
        type		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url		: url, // the url where we want to POST
        data		: formData, // our data object
        dataType	: 'text', // what type of data do we expect back from the server
        processData: false,
        contentType: false,
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
                $('#saved').removeClass('hidden');
                getAllRevue("");
            } else {
                $('#saved').removeClass('hidden');
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