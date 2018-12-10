var url = "composant/com_IAintegration/controllerIAintegration.php";
var datatable = "";
$(function () {
    haveSession();

    $('.btn[id="modal_add_key"]').click(function () {
        $('.modal[id="modal_add_key"]').modal('show');
        $('#modal_add_key select').select2({
            language: "fr"
        });
    });
});
