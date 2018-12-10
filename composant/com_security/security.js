var url = "composant/com_security/controlersecurity.php";
var datatable = "";
$(function () {
    //lock esc in login
    $('#str_LOGIN').on("keydown", function(e){
        var value = $(this).val();
        if(e.keyCode==32){
            e.preventDefault();
            return false;
        } 
    });
    $('#str_MOBILE').on("keydown", function(e){
        var value = $(this).val();
        if(e.keyCode==32){
            e.preventDefault();
            return false;
        } 
    });
    getUser("");
    getAccountCustomer();
    $('#edit_user').submit(function (e) {
        e.preventDefault();
        var lg_CUSTOMER_ID = $('#edit_user #lg_CUSTOMER_ID').val();
        var str_NAME = $('#edit_user #str_NAME').val();
        var str_LASTNAME = $('#edit_user #str_LASTNAME').val();
        var str_MOBILE = $('#edit_user #str_MOBILE').val();
        var str_FIXE = $('#edit_user #str_FIXE').val();
        var str_EMAIL = $('#edit_user #str_EMAIL').val();
        var str_ILLUSTRATION = $('#edit_user #str_ILLUSTRATION').val();
        var bool_IS_CURRENT_INSTITUTION = $('#edit_user #bool_IS_CURRENT_INSTITUTION').val();
        
        var str_PASSWORD= $('#edit_user #str_PASSWORD').val();
        var str_CPASSWORD= $('#edit_user #str_CPASSWORD').val();
        var str_USER = $('#edit_user #str_USER').val();
        
        if (lg_CUSTOMER_ID == "" || str_NAME == "" || str_LASTNAME =="" || str_MOBILE == "" || str_FIXE =="" || str_EMAIL == "" || str_USER == "")  
        {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            if(str_CPASSWORD == "" || str_PASSWORD == ""){
                editAccount();
            }
            else
            {
                if(str_CPASSWORD == str_PASSWORD && str_PASSWORD.length > 8){
                    editAccount();
                }
                else
                {
                     swal({
                        title: "Echec",
                        text: "Les deux mot de passe ne sont pas iddentique.",
                        type: "error",
                        confirmButtonText: "Ok"
                    });
                    return false;
                }
            }
        }
    });
    
    $('#modal_edit_key').submit(function (e) {
        e.preventDefault();
        var lg_SECURITY_ID = $('#modal_edit_key #lg_SECURITY_ID').val();
        var str_LOGIN = $('#modal_edit_key #str_LOGIN').val();
        var str_PASSWORD= $('#modal_edit_key #str_PASSWORD').val();
        var str_PASSWORD2= $('#modal_edit_key #str_PASSWORD2').val();
        //alert(str_PASSWORD);return;
        if (lg_SECURITY_ID == "" || str_PASSWORD =="" || str_LOGIN == "" ||  str_PASSWORD.length < 9) {
            swal({
                title: "Echec",
                text: "Veuillez remplir tous les champs.\nSi le problème persiste, assurez-vous que le mot de passe fait au moins 9 caractères.",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
        } else {
            if(str_PASSWORD2 == str_PASSWORD){
                editUserPassword(lg_SECURITY_ID, str_LOGIN, str_PASSWORD);
            }
            else{
                swal({
                title: "Echec",
                text: "Les mots de passe ne sont pas identique.",
                type: "error",
                confirmButtonText: "Ok"
            });
            return false;
            }
        }
    });
});
function getAccountCustomer()
{
    //alert(lg_OFFRE_ID);
    var task = "getAccountCustomer";
    $.get(url + "?task=" + task, function (json, textStatus)
    {
        var obj = $.parseJSON(json);
        if (obj[0].code_statut == "1")
        {

            var results = obj[0].results;
            if (obj[0].results.length > 0)
            {
                $.each(results, function (i, value)
                {
                    $('#edit_user #str_NAME').val(results[i].str_FIRST_NAME);
                    $('#edit_user #str_LASTNAME').val(results[i].str_LAST_NAME);
                    $('#edit_user #str_MOBILE').val(results[i].str_MOBILE_NUMBER);
                    $('#edit_user #str_FIXE').val(results[i].str_NUMBER);
                    $('#edit_user #str_EMAIL').val(results[i].str_EMAIL);
                    $('#edit_user #lg_CUSTOMER_ID').val(results[i].lg_CUSTOMER_ID);
                    $('#edit_user #str_USER').val(results[i].lg_USER_ID);
                    
                    $( "#str_ILLUSTRATION_ID" ).attr('src', './composant/com_customer/'+results[i].str_ILLUSTRATION);
                    $( "#str_ILLUSTRATION_ID" ).attr('alt', 'image '+results[i].str_FIRST_NAME);
                    $('#edit_user #str_LOGIN').val(results[i].str_LOGIN);
                    $('#edit_user #str_INSTITUTION').val( results[i].lg_INSTITUTION_ID );
                });
            }
        }
    });
}
function editAccount() {
    var form = $('#edit-key-form-user').get(0);
    var formData = new FormData(form);
    $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url		: url, // the url where we want to POST
            data	: formData, // our data object
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
                
                $.ajax("./services/center.php" + "?task=showUserAccount")
                    .done(function(data, status, jqxhr){
                        $('#contenue-application').empty();
                        $('#contenue-application').append(jqxhr.responseText).fadeIn();
                });
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
                
            }
        }
    });
}
function getUser(lg_SECURITY_ID){
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'getUser=getUser&lg_SECURITY_ID=' + lg_SECURITY_ID,
        dataType: 'text',
        success: function (response) {
            //alert(json);return;
            var obj = $.parseJSON(response);
            if (obj[0].code_statut == "1")
            {
                var results = obj[0].results;
            
                if (obj[0].results.length > 0)
                {
                    $.each(results, function (i, value)
                    {
                        $('#str_LOGIN').val(results[i].str_LOGIN);
                        $('#lg_SECURITY_ID').val(results[i].lg_SECURITY_ID);
                    });
                }
            }
        }
    });
}
function editUserPassword(lg_SECURITY_ID, str_LOGIN, str_PASSWORD){
    $.ajax({
        url: url, // La ressource ciblée
        type: 'POST', // Le type de la requête HTTP.
        data: 'editUserPassword=editUserPassword&lg_SECURITY_ID=' + lg_SECURITY_ID + '&str_LOGIN=' + str_LOGIN+"&str_PASSWORD="+str_PASSWORD,
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
                    confirmButtonText: "Ok"},
                        function (isConfirm) {
                            if (isConfirm) {
                                //alert("ok")
                                window.location.href = "login.php";
                            }
                }
            );
            } else {
                //alert(obj[0].results);
                swal({
                    title: "Echec de l'opéraion",
                    text: obj[0].results,
                    type: "error",
                    confirmButtonText: "Ok"
                });
            }
        }
    });
}