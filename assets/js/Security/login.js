

$('#submitForgottenPassword').click(submitFormModal);

function submitFormModal() {
    let $form = $("form[name='forgotten_password']");
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: $form.serialize(),
        beforeSend: function () {
            /*make som animation*/

        }
    }).done(function (data) {
        $('#modalForgotPassword').modal('dispose');
        $('#modalForgottenPasswordHook').empty();
        $(data).appendTo('#modalForgottenPasswordHook');
        $('#modalForgotPassword').modal();
        $('#modalForgotPassword').modal('show');
        $('#submitForgottenPassword').click(submitFormModal);

    })
}