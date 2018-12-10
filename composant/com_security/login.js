
$('#soumettre').on('click', function(e){
    e.preventDefault();
    var str_LOGIN = $("#frm-add #str_LOGIN").val();
    var str_PASSWORD = $("#frm-add #str_PASSWORD").val();
   /* if(str_PASSWORD == "" || str_LOGIN == ""){
       // alert('ok')
        return false;
    }*/
    //alert(str_PASSWORD);
    var task = "connexion";
    var url = "composant/com_security/controlersecurity.php";
    $.ajax({
            url: url,
            type: 'POST',
            data: "task="+task+"&str_LOGIN="+str_LOGIN+"&str_PASSWORD="+str_PASSWORD,
            dataType: 'text',
            cache: false,
            success: function (response)
            {
                var obj = $.parseJSON(response);
                if (obj[0].code_statut == "1")
                {
                   window.location.href = "index.php";
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
});