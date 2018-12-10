/*$('#logout').on('submit', function(e){
    e.preventDefault();*/
    var task = "disconnect";
    var url = "composant/com_security/controlersecurity.php";
    $.ajax({
            url: url, 
            type: 'GET',
            data: "task="+task,
            dataType: 'text',
            cache: false,
            success: function (response) 
            {
                var obj = $.parseJSON(response);
                if (obj[0].code_statut == "1")
                {
                   window.location.href = "login.php";
                } 
                else 
                {
                     swal({
                         title: obj[0].desc_statut,
                         text: obj[0].results,
                         type: "error",
                         confirmButtonText: "Ok"
                     });
                }
            }
        });
//});