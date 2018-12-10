$(document).ready(function(){
    $(":file").filestyle();
    $(":file").filestyle({buttonText: "Charger fichier"});
    $(":file").filestyle({buttonName: "btn-primary"});
    $(":file").filestyle({iconName: "glyphicon-inbox"});
    $(":file").filestyle({buttonBefore: true});
    $(":file").filestyle({placeholder: "Aucun fichier selectionné"});
    $("#submit").on('click', function(){

    });
});