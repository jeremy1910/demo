


$('#submitForgottenPassword').click(submitFormModal);


function submitFormModal(e) {
    e.preventDefault();
    let $form = $("form[name='forgotten_password']");
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: $form.serialize(),
        beforeSend: function () {
            /*make som animation*/

        }
    }).done(function (data) {
        $('#modalForgotPassword').modal('hide');

        $('#modalForgotPassword').on('hidden.bs.modal', function (e) {
            $('#modalForgottenPasswordHook > *').replaceWith(data);
            $('#modalForgotPassword').modal('show');
            $('#submitForgottenPassword').click(submitFormModal);
        })



        //$(data).appendTo('#modalForgottenPasswordHook');
        //$('#modalForgotPassword').modal();



    })
}